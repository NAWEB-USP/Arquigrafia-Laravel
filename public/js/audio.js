import { record } from "./vmsg/vmsg.js";

let recordButton = document.getElementById("record");
recordButton.onclick = function() {
  record({wasmURL: "https://arquigrafia.rckt.com.br/js/vmsg.wasm"}).then(blob => {
    // window.arquigrafia.blob = blob;

    // var url = URL.createObjectURL(blob);
    // var preview = document.createElement('audio');
    // preview.controls = true;
    // preview.src = url;
    // document.body.appendChild(preview);

    $('.vmsg-save-button').css({opacity: 0.5}).prop('disabled','disabled');

    var fd = new FormData();
    fd.append('audio', blob);
    fd.append('user', window.arquigrafia.user);
    fd.append('photo', window.arquigrafia.photo);
    var uri = "/oam/audios";
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = e => {
      if (xhr.readyState === 4 && xhr.status === 200) {
        location.reload();
      }
    };
    xhr.open("POST", uri, true);
    xhr.send(fd);

  });
};
