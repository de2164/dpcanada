
function $(arg) {
    return document.getElementById(arg);
}
function eCheckAll(e) {
    var is_ck, chk;
    if(! e) e = window.event;

    is_ck = $("chkall").checked;

    var chks = document.getElementsByTagName("input");
    for(var i = 0; i < chks.length; i++) {
        chk = chks[i];
        chk.checked = is_ck;
    }
}

function econfirm(e) {
    var evt = e ? e:window.event;
    var c = window.confirm('Confirm you wish to delete files and/or pages');
    return c;
}

function eSetPath(path) {
    if(path == '..') {
        $("uploadpath").value = $("uploadpath").value.replace(/\/[^\/]*$/, ""); 
    }
    else {
        $("uploadpath").value = path;
    }
    $("workform").submit();
}

function eFileSelect() {
    $("uploading").style.visibility = "hidden";
    $("upbutton").style.visibility = "visible";
}

function eUpClick() {
    $("uploading").style.visibility = "visible";
    $("upform").submit();
}

function zipfile() {
    return $("subjectfilename");
}
