// Hide messages that are being display to the user when it click on them

let message;

document.addEventListener("DOMContentLoaded", function () {
  message = document.querySelector(".tirage-info");

  if (typeof message !== undefined && message !== null) {
    message.addEventListener("click", hideInfo);
  }
});

function hideInfo() {
  message.remove();
}
