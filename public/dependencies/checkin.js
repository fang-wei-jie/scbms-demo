// custom source code of https://gist.github.com/dvergeylen/a256deb3182b8a863238fcc0704aecb9

window.onload = function () {
    /* Ask for "environnement" (rear) camera if available (mobile), will fallback to only available otherwise (desktop).
     * User will be prompted if (s)he allows camera to be started */
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" }, audio: false }).then(function (stream) {
        var video = document.getElementById("video-preview");
        video.srcObject = stream;
        video.setAttribute("playsinline", true); /* otherwise iOS safari starts fullscreen */
        video.play();
        setTimeout(tick, 100); /* We launch the tick function 100ms later (see next step) */
    })
        .catch(function (err) {
            console.log(err); /* User probably refused to grant access*/
        });
};

function tick() {
    var video = document.getElementById("video-preview");
    var qrCanvasElement = document.getElementById("qr-canvas");
    var qrCanvas = qrCanvasElement.getContext("2d");
    var width, height;

    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        qrCanvasElement.height = video.videoHeight;
        qrCanvasElement.width = video.videoWidth;
        qrCanvas.drawImage(video, 0, 0, qrCanvasElement.width, qrCanvasElement.height);
        try {
            var result = qrcode.decode();
            queryResult(result); //call to my queryResult function
            /* Video can now be stopped */
            video.pause();
            video.src = "";
            video.srcObject.getVideoTracks().forEach(track => track.stop());

            /* Display Canvas and hide video stream */
            qrCanvasElement.classList.remove("hidden");
            video.classList.add("hidden");
            $(".video-container").hide()
        } catch (e) {
            /* No Op */
        }
    }

    /* If no QR could be decoded from image copied in canvas */
    if (!video.classList.contains("hidden"))
        setTimeout(tick, 100);
}

function queryResult (result) {
    $("#code").val(result)
    document.getElementById('checkinForm').submit()
}
