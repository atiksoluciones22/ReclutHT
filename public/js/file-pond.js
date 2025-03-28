function handleFileUpload(inputId, label, eventName, defaultFile = null) {
    const inputElement = document.getElementById(inputId);
    const pond = FilePond.create(inputElement, {
        labelIdle: label,
    });

    if (defaultFile) {
        pond.addFile(defaultFile);
    }

    pond.on('updatefiles', (fileItems) => {
        fileItems.forEach((fileItem) => {
            const reader = new FileReader();
            reader.onloadend = () => {
                const base64String = reader.result;
                Livewire.dispatch(eventName, [base64String]);
            };
            reader.readAsDataURL(fileItem.file);
        });
    });

    pond.on('removefile', (fileItem) => {
        Livewire.dispatch('fileUploaded', ['']);
    });
}
