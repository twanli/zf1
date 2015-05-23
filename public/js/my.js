$(document).ready(function() {
    var songsGallery = $.songsGallery();
    var url = window.location.href;

    if(url.indexOf("apply-reorder") > -1) {
        songsGallery.sort();

    }
});