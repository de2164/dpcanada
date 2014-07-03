function $(str) {
    return document.getElementById(str);
}

// called by clicking a column caption
function eSetSort(e) {
    if(!e) e = window.event;

    // may only sort on some columns
    var tgt = e.target ? e.target : e.srcElement;
    var key = tgt.id;
    switch(key) {
        case "lktitle":
        case "lkauthor":
        case "lklang":
        case "lkprojid":
        case "lkgenre":
        case "lkpm":
        case "lkdiff":
        case "lkround":
            break;
        default:
            return;
    }
    // pull values of hidden inputs
    var vsort = $("sort");
    var vdesc = $("desc");


    // if clicked current sort column, reverse direction
    if( vsort.value === key ) {
        vdesc.value = (vdesc.value == '0') ? '1' : '0' ;
    }
    else {
        vsort.value = key ;
        vdesc.value = '0' ;
    }
    // submit the form
    var sf = $('searchform');
    sf.submit();
}
