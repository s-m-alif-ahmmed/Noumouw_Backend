<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Chunk Upload</title>
    <style>
        #progressBar {
            width: 100%;
            background: #eee;
            border-radius: 6px;
            overflow: hidden;
            height: 20px;
            margin-top: 8px;
        }
        #progressFill { height:100%; width:0%; background: linear-gradient(90deg,#4caf50,#8bc34a); }
    </style>
</head>
<body>

<form id="uploadForm">
    @csrf
    <label>File(s): <input type="file" id="fileInput" multiple /></label><br/>
{{--    <label>Chunk size (MB): <input type="number" id="chunkSizeMB" value="2" min="1" /></label><br/>--}}
    <button type="submit">Upload</button>
</form>

<div id="status"></div>
<div id="progressBar" style="display:none"><div id="progressFill"></div></div>

<script>
    const form = document.getElementById('uploadForm');
    const fileInput = document.getElementById('fileInput');
    const status = document.getElementById('status');
    const progressBar = document.getElementById('progressBar');
    const progressFill = document.getElementById('progressFill');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const files = Array.from(fileInput.files);
        if (!files.length) return alert("Select at least one file.");

        const chunkSizeMB = parseInt(document.getElementById('chunkSizeMB')?.value) || 2;
        const chunkSize = chunkSizeMB * 1024 * 1024;

        progressBar.style.display = 'block';
        progressFill.style.width = '0%';

        for (const file of files) {
            status.innerText = `Uploading ${file.name}...`;
            let tempId = null;
            const totalChunks = Math.ceil(file.size / chunkSize);

            for (let index = 1; index <= totalChunks; index++) {
                const start = (index - 1) * chunkSize;
                const end = Math.min(start + chunkSize, file.size);
                const chunk = file.slice(start, end);

                const formData = new FormData();
                formData.append('chunk', chunk);
                formData.append('file_name', file.name);
                formData.append('index', index);
                formData.append('total_chunks', totalChunks);
                if (tempId) formData.append('temp_id', tempId);

                try {
                    const res = await fetch("{{ route('upload-file') }}", {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    });

                    if (!res.ok) {
                        const text = await res.text();
                        throw new Error(`HTTP ${res.status}: ${text}`);
                    }

                    const data = await res.json();

                    if (!tempId && data.temp_id) tempId = data.temp_id;

                    const uploadedChunks = data.uploaded_chunks ?? index;
                    const progress = Math.round((uploadedChunks / totalChunks) * 100);
                    progressFill.style.width = progress + '%';
                    status.innerText = `${file.name} — chunk ${index}/${totalChunks} — ${progress}%`;

                    if (data.is_completed) {
                        status.innerText = `${file.name} uploaded successfully: ${data.file_path ?? 'stored'}`;
                    }
                } catch (err) {
                    console.error('Upload error', err);
                    alert('Upload failed for chunk ' + index + ': ' + err.message);
                    return;
                }
            }
        }

        alert('All uploads completed');
        progressBar.style.display = 'none';
        status.innerText = '';
    });
</script>

</body>
</html>
