///////////comments////////////////
function SetChecked(val, chkName, form) {
    dml = document.forms[form];
    len = dml.elements.length;
    var i = 0;
    for (i = 0; i < len; i++) {
        if (dml.elements[i].name == chkName) {
            dml.elements[i].checked = val;
        }
    }
}


function ValidateForm(dml, chkName) {
    len = dml.elements.length;
    var i = 0;
    for (i = 0; i < len; i++) {
        if ((dml.elements[i].name == chkName) && (dml.elements[i].checked == 1)) return true
    }
    alert("Please select at least one record to be deleted")
    return false;
}

//////////////////////////////

var form2 = 'frmSample2' //Give the form name here

function SetChecked2(val, chkName) {
    dml2 = document.forms[form2];
    len2 = dml2.elements.length;
    var i = 0;
    for (i = 0; i < len2; i++) {
        if (dml2.elements[i].name == chkName) {
            dml2.elements[i].checked = val;
        }
    }
}

function ValidateForm2(dml2, chkName) {
        len2 = dml2.elements.length;
        var i = 0;
        for (i = 0; i < len2; i++) {
            if ((dml2.elements[i].name == chkName) && (dml2.elements[i].checked == 1)) return true
        }
        alert("Please select at least one record to be deleted")
        return false;
    }
    ///////////comments////////////////