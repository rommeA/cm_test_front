function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

$('.locale-btn').on('click', function(e){
    // e.preventDefault();
    localStorage.clear();
    let hash = window.location.hash;
    let old_href = $(this).attr('href');
    $(this).attr('href', old_href + '/' + hash.split('#').join('$'));
    // console.log($(this).attr('href'));
})
