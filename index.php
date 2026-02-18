<?php
$storageDir = "uploads/";
if (!is_dir($storageDir)) { mkdir($storageDir, 0777, true); }

$statusMsg = "";
$statusType = "";

// Handle File Upload
if (isset($_FILES['fileToUpload'])) {
    $targetFile = $storageDir . basename($_FILES["fileToUpload"]["name"]);
    if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        $statusMsg = "File uploaded successfully!";
        $statusType = "green";
    }
}

// Handle File Deletion
if (isset($_GET['delete'])) {
    $fileToDelete = basename($_GET['delete']); // security: basename prevents directory traversal
    $filePath = $storageDir . $fileToDelete;

    if (file_exists($filePath)) {
        unlink($filePath);
        $statusMsg = "File deleted successfully.";
        $statusType = "red";
    }
}

// Get file list
$files = array_diff(scandir($storageDir), array('.', '..'));
$fileCount = count($files);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SentFile Pro | Manage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-50 p-4 md:p-10 font-sans">

    <div class="max-w-5xl mx-auto">
        <?php if($statusMsg): ?>
            <div class="mb-6 p-4 rounded-xl border bg-<?php echo $statusType; ?>-100 border-<?php echo $statusType; ?>-200 text-<?php echo $statusType; ?>-700 flex items-center shadow-sm animate-pulse">
                <i class="fa-solid fa-circle-info mr-3"></i> <?php echo $statusMsg; ?>
            </div>
        <?php endif; ?>

        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Sent<span class="text-blue-600">File</span></h1>
                <p class="text-slate-500 font-medium">Internal Storage Dashboard</p>
            </div>
            <div class="bg-white border border-slate-200 px-6 py-3 rounded-2xl flex items-center gap-4 shadow-sm">
                <div class="text-right">
                    <p class="text-xs text-slate-400 font-bold uppercase">Total Files</p>
                    <p class="text-xl font-black text-blue-600"><?php echo $fileCount; ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-4">Quick Upload</h3>
                    <form action="" method="post" enctype="multipart/form-data">
                        <label class="group w-full flex flex-col items-center px-4 py-8 bg-slate-50 text-blue-600 rounded-2xl border-2 border-dashed border-slate-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm font-semibold">Select a file</span>
                            <input type='file' name="fileToUpload" class="hidden" onchange="this.form.submit()" />
                        </label>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase">File Name</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if($fileCount > 0): ?>
                                <?php foreach ($files as $file): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500">
                                                <i class="fa-solid fa-file-lines"></i>
                                            </div>
                                            <span class="font-semibold text-slate-700 truncate max-w-[250px]"><?php echo $file; ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex gap-2">
                                            <a href="<?php echo $storageDir.$file; ?>" download class="p-2 px-4 bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-600 rounded-xl transition-all text-sm font-bold">
                                                Download
                                            </a>
                                            <a href="?delete=<?php echo urlencode($file); ?>" onclick="return confirm('Delete this file?')" class="p-2 px-3 bg-red-50 hover:bg-red-500 hover:text-white text-red-500 rounded-xl transition-all">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="px-6 py-20 text-center text-slate-400 font-medium">
                                        Your storage is empty.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
