var listeRangs = document.getElementsByTagName("tr");
var form = document.getElementsByTagName("form")[0];
var formBoxHide = document.getElementById("formbox-hide");
var formFieldMode = document.getElementById("formfield-mode");

// show whole table
function showTable() {
	for (i = 0; i < listeRangs.length; i++) {
		listeRangs[i].style.display = "table-row";
	}
}

// get author field from given row
function getAuthor(row) {
	return row.children[0].innerHTML;
}

// hide given element
function hideElement(element) {
	element.style.display = ("none");
}

// filter table rows by author name
// if "author" is unset or empty, show all entries
// if "author" is set, show only entries by this author
function filterByAuthor() {
	var promptMessage = "Entrez le pseudonyme sous lequel votre question est enregistrée.";
	var author = prompt(promptMessage).toLowerCase();
	if (! author || author == '') {
		showTable();
	}
	else {
		for (i = 0; i < listeRangs.length; i++) {
			if (getAuthor(listeRangs[i]).toLowerCase() !== author) {
				hideElement(listeRangs[i]);
			}
		}
	}
}

// filter table rows by hide status
function filterByHide(button) {
	// all entries are shown, mask hidden entries
	if (button.innerHTML == "Masquer les entrées cachées") {
		for (i = 0; i < listeRangs.length; i++) {
			if (listeRangs[i].getAttribute("hide") == '1') {
				hideElement(listeRangs[i]);
			}
		}
		button.innerHTML = "Afficher toutes les entrées";
	}
	// hidden entries are masked, show all entries
	else {
		showTable();
		button.innerHTML = "Masquer les entrées cachées";
	}
}

// switch to edition mode
function openForm(id) {
	var question = document.getElementById(id).innerHTML;
	var listeBoutons = document.getElementsByClassName("button-filter");
	var formFieldId = document.getElementById("formfield-id");
	var formFieldHide = document.getElementById("formfield-hide");
	var formFieldQuestion = document.getElementById("formfield-question");
	// populate form
	formFieldId.setAttribute("value", id);
	formFieldQuestion.setAttribute("value", question);
	if (document.getElementById(id).parentElement.getAttribute("hide") == '1') {
		formBoxHide.checked = true;
	} else {
		formBoxHide.checked = false;
	}
	checkIsHidden(formBoxHide);
	// hide all rows
	for (i = 0; i < listeRangs.length; i++) {
		hideElement(listeRangs[i]);
	}
	// hide filter buttons
	for (i = 0; i < listeBoutons.length; i++) {
		hideElement(listeBoutons[i]);
	}
	// show form
	form.style.display = ("block");
}

// check hide status of current entry
function checkIsHidden() {
	var formFieldHide = document.getElementById("formfield-hide");
	if (formBoxHide.checked) {
		formFieldHide.setAttribute("value", 1);
	} else {
		formFieldHide.setAttribute("value", 0);
	}
}

// check to delete status of current entry
function checkToDelete(button) {
	if (button.checked) {
		formFieldMode.setAttribute("value", "delete");
	} else {
		formFieldMode.setAttribute("value", "update");
	}
}

// submit form
function submitForm() {
	var confirmMessage = "Êtes-vous sûr de vouloir supprimer définitivement cette entrée ?";
	// ask for confirmation for deletion
	if (formFieldMode.getAttribute("value") !== "delete" || confirm(confirmMessage)) {
		form.submit();
	}
}
