$(document).ready(function () {
    $('#video_upload_1').on('change', function (event) {
        var file = this.files[0];
        var type = file.type;
        var videoNode = document.querySelector('#video1');
        var canPlay = videoNode.canPlayType(type);
        if (canPlay === '')
            canPlay = 'no';
        var isError = canPlay === 'no';
        alert(isError);
        if (isError) {
            return;
        }
        videoNode.src = createObjectURL(file);
        videoNode.controls = true;
    });
});

