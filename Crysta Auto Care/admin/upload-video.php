<?php
/**
 * Admin Upload Video - Crysta Auto Care
 * Upload (chunked), edit, replace, and delete videos
 */
require_once 'auth.php';
require_once '../includes/db.php';

$upload_dir = '../uploads/videos/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// ─── AJAX: Chunked Upload Handler ───
// Splits large files into small chunks (< 2MB each) to bypass PHP upload_max_filesize
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['chunk_upload'])) {
    header('Content-Type: application/json');

    $chunk_index = intval($_POST['chunk_index'] ?? -1);
    $total_chunks = intval($_POST['total_chunks'] ?? 0);
    $upload_id = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['upload_id'] ?? '');
    $original_name = $_POST['original_name'] ?? 'video.mp4';
    $title = trim(htmlspecialchars($_POST['title'] ?? ''));

    if ($chunk_index < 0 || $total_chunks < 1 || empty($upload_id)) {
        echo json_encode(['success' => false, 'error' => 'Invalid chunk parameters.']);
        exit;
    }

    // Create temp directory for chunks
    $temp_dir = $upload_dir . 'chunks_' . $upload_id . '/';
    if (!is_dir($temp_dir)) mkdir($temp_dir, 0777, true);

    // Save chunk
    if (!isset($_FILES['chunk']) || $_FILES['chunk']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'error' => 'Failed to receive chunk ' . ($chunk_index + 1)]);
        exit;
    }

    if (!move_uploaded_file($_FILES['chunk']['tmp_name'], $temp_dir . 'chunk_' . str_pad($chunk_index, 5, '0', STR_PAD_LEFT))) {
        echo json_encode(['success' => false, 'error' => 'Failed to save chunk ' . ($chunk_index + 1)]);
        exit;
    }

    // If not the last chunk, just confirm receipt
    if ($chunk_index < $total_chunks - 1) {
        echo json_encode(['success' => true, 'message' => 'Chunk ' . ($chunk_index + 1) . '/' . $total_chunks]);
        exit;
    }

    // LAST CHUNK: Merge all chunks into final file
    $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    $allowed = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'];

    if (!in_array($ext, $allowed)) {
        array_map('unlink', glob($temp_dir . '*'));
        rmdir($temp_dir);
        echo json_encode(['success' => false, 'error' => 'Invalid file type (.' . $ext . '). Allowed: ' . implode(', ', $allowed)]);
        exit;
    }

    $final_name = 'video_' . time() . '_' . uniqid() . '.' . $ext;
    $final_path = $upload_dir . $final_name;

    $fp = fopen($final_path, 'wb');
    if (!$fp) {
        array_map('unlink', glob($temp_dir . '*'));
        rmdir($temp_dir);
        echo json_encode(['success' => false, 'error' => 'Cannot write to upload directory. Check permissions.']);
        exit;
    }

    for ($i = 0; $i < $total_chunks; $i++) {
        $chunk_file = $temp_dir . 'chunk_' . str_pad($i, 5, '0', STR_PAD_LEFT);
        if (!file_exists($chunk_file)) {
            fclose($fp);
            if (file_exists($final_path)) unlink($final_path);
            array_map('unlink', glob($temp_dir . '*'));
            rmdir($temp_dir);
            echo json_encode(['success' => false, 'error' => 'Missing chunk ' . ($i + 1) . '. Please re-upload.']);
            exit;
        }
        fwrite($fp, file_get_contents($chunk_file));
        unlink($chunk_file);
    }
    fclose($fp);
    @rmdir($temp_dir);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO gallery (video, title) VALUES (?, ?)");
    $stmt->bind_param("ss", $final_name, $title);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Video uploaded successfully!', 'done' => true]);
    } else {
        if (file_exists($final_path)) unlink($final_path);
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $conn->error]);
    }
    $stmt->close();
    exit;
}

$message = '';
$msg_type = '';

// ─── Handle video title edit ───
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_video_id'])) {
    $edit_id = intval($_POST['edit_video_id']);
    $new_title = trim(htmlspecialchars($_POST['edit_title'] ?? ''));

    $stmt = $conn->prepare("UPDATE gallery SET title = ? WHERE id = ?");
    $stmt->bind_param("si", $new_title, $edit_id);
    if ($stmt->execute() && $stmt->affected_rows >= 0) {
        $message = 'Video title updated successfully!';
        $msg_type = 'green';
    } else {
        $message = 'Failed to update video title.';
        $msg_type = 'red';
    }
    $stmt->close();
}

// ─── Handle video replace ───
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['replace_video_id'])) {
    $replace_id = intval($_POST['replace_video_id']);

    if (!isset($_FILES['replace_video']) || $_FILES['replace_video']['error'] === UPLOAD_ERR_NO_FILE) {
        $message = 'Please select a new video file.';
        $msg_type = 'red';
    } elseif ($_FILES['replace_video']['error'] !== UPLOAD_ERR_OK) {
        $message = 'Upload error. File may be too large for direct replace. Delete and re-upload using the main form.';
        $msg_type = 'red';
    } else {
        $ext = strtolower(pathinfo($_FILES['replace_video']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'];

        if (!in_array($ext, $allowed_extensions)) {
            $message = 'Only video files are allowed.';
            $msg_type = 'red';
        } else {
            $stmt = $conn->prepare("SELECT video FROM gallery WHERE id = ?");
            $stmt->bind_param("i", $replace_id);
            $stmt->execute();
            $old = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($old) {
                $old_path = $upload_dir . $old['video'];
                if (file_exists($old_path)) unlink($old_path);
            }

            $new_filename = 'video_' . time() . '_' . uniqid() . '.' . $ext;
            $dest_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['replace_video']['tmp_name'], $dest_path)) {
                $stmt = $conn->prepare("UPDATE gallery SET video = ? WHERE id = ?");
                $stmt->bind_param("si", $new_filename, $replace_id);
                $stmt->execute();
                $stmt->close();
                $message = 'Video file replaced successfully!';
                $msg_type = 'green';
            } else {
                $message = 'Failed to upload replacement video.';
                $msg_type = 'red';
            }
        }
    }
}

// ─── Handle video delete ───
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_video_id'])) {
    $delete_id = intval($_POST['delete_video_id']);
    $stmt = $conn->prepare("SELECT video FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($result) {
        $file_path = $upload_dir . $result['video'];
        if (file_exists($file_path)) unlink($file_path);

        $stmt = $conn->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
        $message = 'Video deleted successfully.';
        $msg_type = 'green';
    }
}

// Fetch existing videos
$videos = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
$current_admin_page = 'gallery';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery | Crysta Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .upload-shimmer {
            background: linear-gradient(90deg, transparent 25%, rgba(251,191,36,0.08) 50%, transparent 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
    </style>
</head>
<body class="bg-black text-white min-h-screen">
    <!-- Admin Navigation -->
    <nav class="border-b border-gray-800 bg-black/95 sticky top-0 z-50" style="backdrop-filter: blur(10px);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="dashboard.php" class="flex items-center space-x-2">
                        <img src="../includes/logo.png" alt="Crysta" class="h-8 w-auto object-contain">
                        <span class="text-sm font-bold tracking-wider">CRYSTA ADMIN</span>
                    </a>
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="dashboard.php" class="text-xs tracking-wider uppercase text-gray-400 hover:text-amber-400 transition-colors">Dashboard</a>
                        <a href="bookings.php" class="text-xs tracking-wider uppercase text-gray-400 hover:text-amber-400 transition-colors">Bookings</a>
                        <a href="upload-video.php" class="text-xs tracking-wider uppercase text-amber-400 border-b border-amber-400 pb-0.5">Gallery</a>
                        <a href="add-blog.php" class="text-xs tracking-wider uppercase text-gray-400 hover:text-amber-400 transition-colors">Blog</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="../index.php" target="_blank" class="hidden sm:inline text-xs text-gray-400 hover:text-white transition-colors">View Site →</a>
                    <a href="dashboard.php?logout=1" class="text-xs text-red-400 hover:text-red-300 transition-colors">Logout</a>
                    <button onclick="document.getElementById('admin-mobile-menu').classList.toggle('hidden')" class="md:hidden p-2 text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div id="admin-mobile-menu" class="hidden md:hidden border-t border-gray-800 bg-black">
            <div class="px-4 py-3 space-y-1">
                <a href="dashboard.php" class="block text-sm py-2 px-3 rounded text-gray-400 hover:bg-gray-900">Dashboard</a>
                <a href="bookings.php" class="block text-sm py-2 px-3 rounded text-gray-400 hover:bg-gray-900">Bookings</a>
                <a href="upload-video.php" class="block text-sm py-2 px-3 rounded text-amber-400 bg-amber-400/10">Gallery</a>
                <a href="add-blog.php" class="block text-sm py-2 px-3 rounded text-gray-400 hover:bg-gray-900">Blog</a>
                <a href="../index.php" target="_blank" class="block text-sm py-2 px-3 rounded text-gray-400 hover:bg-gray-900">View Site →</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <div class="w-1.5 h-8 bg-amber-400 rounded-full"></div>
                <h1 class="text-3xl font-bold">Gallery Management</h1>
            </div>
            <p class="text-gray-400 text-sm ml-5">Upload and manage videos. Uses chunked upload — no file size limits.</p>
        </div>

        <?php if ($message): ?>
        <div class="mb-6 p-4 border rounded-lg <?php echo $msg_type === 'green' ? 'border-green-800 bg-green-900/20' : 'border-red-800 bg-red-900/20'; ?>">
            <div class="flex items-center space-x-3">
                <?php if ($msg_type === 'green'): ?>
                <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <?php else: ?>
                <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php endif; ?>
                <p class="text-sm <?php echo $msg_type === 'green' ? 'text-green-400' : 'text-red-400'; ?>"><?php echo $message; ?></p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Upload Form -->
        <div class="border border-gray-800 rounded-2xl overflow-hidden mb-12">
            <div class="p-6 border-b border-gray-800 bg-gray-900/30">
                <h2 class="text-lg font-bold flex items-center space-x-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Upload New Video</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">Supported: MP4, WebM, OGG, MOV, AVI, MKV — No size limit (chunked upload)</p>
            </div>
            <div class="p-6">
                <form id="uploadForm" class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-semibold tracking-wider uppercase mb-2 text-gray-300">Video Title <span class="text-gray-600">(Optional)</span></label>
                        <input type="text" id="title" name="title"
                               class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-amber-400 focus:outline-none transition-colors placeholder-gray-600"
                               placeholder="e.g., Ceramic Coating on Honda Civic">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold tracking-wider uppercase mb-2 text-gray-300">Video File <span class="text-red-400">*</span></label>
                        <div id="dropZone" class="border-2 border-dashed border-gray-700 rounded-xl p-10 text-center hover:border-amber-400/50 transition-all cursor-pointer relative">
                            <div class="pointer-events-none">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-900 border border-gray-700 flex items-center justify-center">
                                    <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-sm text-gray-300 mb-1 font-medium" id="dropText">Click to browse or drag & drop a video file</p>
                                <p class="text-xs text-gray-600">MP4, WebM, OGG, MOV, AVI, MKV</p>
                            </div>
                            <input type="file" id="video" accept="video/*" required
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>

                        <!-- File info preview -->
                        <div id="fileInfo" class="hidden mt-4 p-4 bg-gray-900/60 border border-gray-800 rounded-xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg bg-amber-400/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold" id="fileName"></p>
                                        <p class="text-xs text-gray-500" id="fileSize"></p>
                                    </div>
                                </div>
                                <button type="button" onclick="clearFile()" class="text-xs text-red-400 hover:text-red-300 px-3 py-1.5 border border-red-800/40 rounded-lg hover:bg-red-900/20 transition-colors">Remove</button>
                            </div>
                        </div>
                    </div>

                    <!-- Upload progress -->
                    <div id="uploadProgress" class="hidden space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-amber-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <span class="text-sm text-gray-300" id="progressLabel">Uploading...</span>
                            </div>
                            <span class="text-sm text-amber-400 font-bold tabular-nums" id="progressPercent">0%</span>
                        </div>
                        <div class="w-full bg-gray-800 rounded-full h-2.5 overflow-hidden">
                            <div id="progressBar" class="bg-gradient-to-r from-amber-500 to-amber-400 h-2.5 rounded-full transition-all duration-300 ease-out" style="width: 0%"></div>
                        </div>
                        <p class="text-xs text-gray-500" id="chunkInfo"></p>
                    </div>

                    <button type="submit" id="uploadBtn" class="px-8 py-3 bg-amber-400 text-black font-semibold text-sm tracking-wider uppercase hover:bg-amber-300 transition-all duration-300 rounded-lg flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>Upload Video</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Existing Videos -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold flex items-center space-x-2">
                    <span>Uploaded Videos</span>
                    <span class="text-sm font-normal text-amber-400/80 bg-amber-400/10 px-2.5 py-0.5 rounded-full"><?php echo $videos ? $videos->num_rows : 0; ?></span>
                </h2>
            </div>
            <?php if ($videos && $videos->num_rows > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($video = $videos->fetch_assoc()): ?>
                <div class="border border-gray-800 rounded-2xl overflow-hidden hover:border-gray-600 transition-all group bg-gray-900/20" id="video-card-<?php echo $video['id']; ?>">
                    <div class="aspect-video bg-gray-900 relative">
                        <video class="w-full h-full object-cover" controls preload="metadata" playsinline>
                            <source src="../uploads/videos/<?php echo htmlspecialchars($video['video']); ?>">
                        </video>
                    </div>
                    <div class="p-4">
                        <!-- Title display -->
                        <div id="title-display-<?php echo $video['id']; ?>">
                            <p class="text-sm font-semibold mb-0.5"><?php echo $video['title'] ? htmlspecialchars($video['title']) : '<span class="text-gray-500 italic">Untitled</span>'; ?></p>
                            <p class="text-xs text-gray-500 mb-3"><?php echo date('M j, Y \a\t g:i A', strtotime($video['created_at'])); ?></p>
                        </div>

                        <!-- Edit title form (hidden) -->
                        <form method="POST" id="edit-form-<?php echo $video['id']; ?>" class="hidden mb-3">
                            <input type="hidden" name="edit_video_id" value="<?php echo $video['id']; ?>">
                            <div class="flex items-center space-x-2">
                                <input type="text" name="edit_title" value="<?php echo htmlspecialchars($video['title'] ?? ''); ?>"
                                       class="flex-1 bg-black border border-gray-600 rounded-lg px-3 py-2 text-sm text-white focus:border-amber-400 focus:outline-none transition-colors placeholder-gray-600"
                                       placeholder="Enter new title">
                                <button type="submit" class="text-xs px-3 py-2 bg-amber-400 text-black rounded-lg font-semibold hover:bg-amber-300 transition-colors">Save</button>
                                <button type="button" onclick="toggleEdit(<?php echo $video['id']; ?>)" class="text-xs px-3 py-2 text-gray-400 border border-gray-700 rounded-lg hover:bg-gray-900 transition-colors">Cancel</button>
                            </div>
                        </form>

                        <!-- Action buttons -->
                        <div class="flex items-center space-x-2 flex-wrap gap-y-2">
                            <button type="button" onclick="toggleEdit(<?php echo $video['id']; ?>)" class="text-xs px-3 py-1.5 text-gray-300 border border-gray-700 rounded-lg hover:bg-gray-900 hover:border-amber-400/30 hover:text-amber-400 transition-colors flex items-center space-x-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Edit Title</span>
                            </button>

                            <button type="button" onclick="toggleReplace(<?php echo $video['id']; ?>)" class="text-xs px-3 py-1.5 text-blue-400 border border-blue-800/50 rounded-lg hover:bg-blue-900/20 transition-colors flex items-center space-x-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Replace</span>
                            </button>

                            <form method="POST" class="inline">
                                <input type="hidden" name="delete_video_id" value="<?php echo $video['id']; ?>">
                                <button type="submit" class="text-xs px-3 py-1.5 text-red-400 border border-red-800/50 rounded-lg hover:bg-red-900/20 transition-colors flex items-center space-x-1" onclick="return confirm('Delete this video permanently?')">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Delete</span>
                                </button>
                            </form>
                        </div>

                        <!-- Replace form (hidden) -->
                        <form method="POST" enctype="multipart/form-data" id="replace-form-<?php echo $video['id']; ?>" class="hidden mt-3 p-3 bg-gray-900/50 border border-gray-800 rounded-lg">
                            <input type="hidden" name="replace_video_id" value="<?php echo $video['id']; ?>">
                            <p class="text-xs text-gray-400 mb-2">Select a new video (small files only for direct replace):</p>
                            <input type="file" name="replace_video" accept="video/*" required
                                   class="w-full text-xs text-gray-400 file:mr-2 file:py-1 file:px-3 file:rounded-md file:border file:border-gray-600 file:text-xs file:font-semibold file:bg-black file:text-white file:cursor-pointer mb-2">
                            <div class="flex items-center space-x-2">
                                <button type="submit" class="text-xs px-3 py-1.5 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-500 transition-colors">Upload & Replace</button>
                                <button type="button" onclick="toggleReplace(<?php echo $video['id']; ?>)" class="text-xs px-3 py-1.5 text-gray-400 border border-gray-700 rounded-lg hover:bg-gray-900 transition-colors">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-20 border border-gray-800 rounded-2xl bg-gray-900/10">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center">
                    <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-semibold mb-1">No Videos Yet</h3>
                <p class="text-gray-500 text-sm">Upload your first video using the form above.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('video');
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const dropText = document.getElementById('dropText');
        const dropZone = document.getElementById('dropZone');

        // ─── File selection ───
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const file = this.files[0];
                fileName.textContent = file.name;
                fileSize.textContent = formatSize(file.size);
                fileInfo.classList.remove('hidden');
                dropText.textContent = 'File selected ✓';
                dropZone.classList.add('border-amber-400/40');
                dropZone.classList.remove('border-gray-700');
            }
        });

        function clearFile() {
            fileInput.value = '';
            fileInfo.classList.add('hidden');
            dropText.textContent = 'Click to browse or drag & drop a video file';
            dropZone.classList.remove('border-amber-400/40');
            dropZone.classList.add('border-gray-700');
        }

        function formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            if (bytes < 1073741824) return (bytes / 1048576).toFixed(1) + ' MB';
            return (bytes / 1073741824).toFixed(2) + ' GB';
        }

        // ─── Drag & drop ───
        ['dragenter', 'dragover'].forEach(evt => {
            dropZone.addEventListener(evt, (e) => { e.preventDefault(); dropZone.classList.add('border-amber-400/60', 'bg-amber-400/5'); });
        });
        ['dragleave', 'drop'].forEach(evt => {
            dropZone.addEventListener(evt, (e) => { e.preventDefault(); dropZone.classList.remove('border-amber-400/60', 'bg-amber-400/5'); });
        });
        dropZone.addEventListener('drop', (e) => {
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });

        // ─── Chunked Upload ───
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!fileInput.files.length) {
                alert('Please select a video file.');
                return;
            }

            const file = fileInput.files[0];
            const CHUNK_SIZE = 1.5 * 1024 * 1024; // 1.5MB per chunk (under PHP's 2M limit)
            const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
            const uploadId = 'up_' + Date.now() + '_' + Math.random().toString(36).substring(2, 11);
            const title = document.getElementById('title').value;

            const progressContainer = document.getElementById('uploadProgress');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            const progressLabel = document.getElementById('progressLabel');
            const chunkInfo = document.getElementById('chunkInfo');
            const uploadBtn = document.getElementById('uploadBtn');

            progressContainer.classList.remove('hidden');
            uploadBtn.disabled = true;
            uploadBtn.classList.add('opacity-50', 'cursor-not-allowed');
            uploadBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> <span>Uploading...</span>';

            let currentChunk = 0;
            let retries = 0;
            const MAX_RETRIES = 3;

            function uploadNextChunk() {
                const start = currentChunk * CHUNK_SIZE;
                const end = Math.min(start + CHUNK_SIZE, file.size);
                const chunk = file.slice(start, end);

                const formData = new FormData();
                formData.append('chunk', chunk, 'chunk');
                formData.append('chunk_upload', '1');
                formData.append('chunk_index', currentChunk);
                formData.append('total_chunks', totalChunks);
                formData.append('upload_id', uploadId);
                formData.append('original_name', file.name);
                formData.append('title', title);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', window.location.href);

                xhr.onload = function() {
                    let response;
                    try {
                        response = JSON.parse(xhr.responseText);
                    } catch (e) {
                        if (retries < MAX_RETRIES) {
                            retries++;
                            setTimeout(uploadNextChunk, 1000);
                            return;
                        }
                        alert('Server returned invalid response. Upload may have failed.');
                        resetUploadUI();
                        return;
                    }

                    if (!response.success) {
                        alert('Upload error: ' + response.error);
                        resetUploadUI();
                        return;
                    }

                    retries = 0;
                    currentChunk++;
                    const pct = Math.round((currentChunk / totalChunks) * 100);
                    progressBar.style.width = pct + '%';
                    progressPercent.textContent = pct + '%';
                    chunkInfo.textContent = 'Chunk ' + currentChunk + ' of ' + totalChunks + ' (' + formatSize(end) + ' / ' + formatSize(file.size) + ')';

                    if (currentChunk < totalChunks) {
                        progressLabel.textContent = 'Uploading... ' + formatSize(end) + ' sent';
                        uploadNextChunk();
                    } else {
                        progressLabel.textContent = 'Upload complete! Refreshing...';
                        progressBar.style.width = '100%';
                        setTimeout(() => window.location.reload(), 800);
                    }
                };

                xhr.onerror = function() {
                    if (retries < MAX_RETRIES) {
                        retries++;
                        chunkInfo.textContent = 'Connection error. Retrying... (' + retries + '/' + MAX_RETRIES + ')';
                        setTimeout(uploadNextChunk, 2000);
                    } else {
                        alert('Upload failed after ' + MAX_RETRIES + ' retries. Check your connection.');
                        resetUploadUI();
                    }
                };

                xhr.send(formData);
            }

            function resetUploadUI() {
                uploadBtn.disabled = false;
                uploadBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                uploadBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg> <span>Upload Video</span>';
                progressContainer.classList.add('hidden');
            }

            uploadNextChunk();
        });

        // ─── Toggle helpers ───
        function toggleEdit(id) {
            document.getElementById('title-display-' + id).classList.toggle('hidden');
            document.getElementById('edit-form-' + id).classList.toggle('hidden');
        }
        function toggleReplace(id) {
            document.getElementById('replace-form-' + id).classList.toggle('hidden');
        }
    </script>
</body>
</html>
