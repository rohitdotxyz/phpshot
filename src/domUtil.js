// modal
const successModal = document.querySelector("#success_modal");
const errorModal = document.querySelector("#error__modal");

// overlay
const bgModal = document.querySelector("#overlay");

if (bgModal) {
    bgModal.addEventListener("click", hideBgModal)
}


let bgLayerClicked = 0;

function showBgModal() {
    bgModal.classList.remove("hidden");
    bgModal.classList.remove("opacity-0");
    document.body.classList.add("overflow-hidden")
}

function hideBgModal() {

    if (bgLayerClicked) {
        return false;
    }

    bgModal.classList.add("opacity-0");
    bgModal.classList.add("hidden");
}

errorModal.addEventListener("click", function (e) {

    if (e.target.closest("#content")) {
        return false;
    }

    addClasses(errorModal, "opacity-0 invisible")
    addClasses(errorModal.firstElementChild, "scale-50 opacity-0 invisible")
    document.body.classList.remove("overflow-hidden")
})
