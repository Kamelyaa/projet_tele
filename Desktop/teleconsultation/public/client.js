const localVideo = document.getElementById('localVideo');
const remoteVideo = document.getElementById('remoteVideo');
const startCallBtn = document.getElementById('startCall');
const hangupBtn = document.getElementById('hangUp');
let localStream;
let remoteStream;
let rtcPeerConnection;
const socket = io();

async function startCall() {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        localVideo.srcObject = localStream;

        rtcPeerConnection = new RTCPeerConnection();
        rtcPeerConnection.addStream(localStream);

        rtcPeerConnection.onaddstream = handleRemoteStreamAdded;

        const offer = await rtcPeerConnection.createOffer();
        await rtcPeerConnection.setLocalDescription(offer);

        socket.emit('offer', offer);
    } catch (error) {
        console.error('Erreur lors du démarrage de l\'appel : ', error);
    }
}

function handleRemoteStreamAdded(event) {
    remoteStream = event.stream;
    remoteVideo.srcObject = mergeMediaStreams(localStream, remoteStream);
}

function mergeMediaStreams(localStream, remoteStream) {
    const canvas = Object.assign(document.createElement('canvas'), { width: 640, height: 480 });
    const ctx = canvas.getContext('2d');
    ctx.drawImage(localVideo, 0, 0, canvas.width / 2, canvas.height);
    ctx.drawImage(remoteVideo, canvas.width / 2, 0, canvas.width / 2, canvas.height);
    canvas.captureStream = canvas.captureStream || canvas.mozCaptureStream;
    return canvas.captureStream();
}

async function hangupCall() {
    rtcPeerConnection.close();
    localVideo.srcObject = null;
    remoteVideo.srcObject = null;
}

startCallBtn.addEventListener('click', startCall);
hangupBtn.addEventListener('click', hangupCall);

