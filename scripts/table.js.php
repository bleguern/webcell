<?php
	session_start();
	include_once(dirname(__FILE__).'/../config/global.php');
?>

function sortTable(id, col, rev) {

  var tblEl = document.getElementById(id);
  var sortLimit = <?php echo $global['table_sort_limit']; ?>;

  if (tblEl.rows.length > sortLimit) {
  	if(!confirm('Il y a plus de ' + sortLimit + ' lignes a trier, etes vous sur de vouloir continuer?')) {
   		return false;
   	}
  }

  if (tblEl.reverseSort == null) {
    tblEl.reverseSort = new Array();
    tblEl.lastColumn = 1;
  }

  if (tblEl.reverseSort[col] == null)
    tblEl.reverseSort[col] = rev;

  if (col == tblEl.lastColumn)
    tblEl.reverseSort[col] = !tblEl.reverseSort[col];

  tblEl.lastColumn = col;

  var oldDsply = tblEl..display;
  tblEl..display = "none";

  var tmpEl;
  var i, j;
  var minVal, minIdx;
  var testVal;
  var cmp;

  for (i = 0; i < tblEl.rows.length - 1; i++) {

    minIdx = i;
    minVal = getTextValue(tblEl.rows[i].cells[col]);

    for (j = i + 1; j < tblEl.rows.length; j++) {
      testVal = getTextValue(tblEl.rows[j].cells[col]);
      cmp = compareValues(minVal, testVal);
      
	  if (tblEl.reverseSort[col])
        cmp = -cmp;
      
	  if (cmp == 0 && col != 1)
        cmp = compareValues(getTextValue(tblEl.rows[minIdx].cells[1]),
                            getTextValue(tblEl.rows[j].cells[1]));
      
	  if (cmp > 0) {
        minIdx = j;
        minVal = testVal;
      }
    }

    if (minIdx > i) {
      tmpEl = tblEl.removeChild(tblEl.rows[minIdx]);
      tblEl.insertBefore(tmpEl, tblEl.rows[i]);
    }
  }

  makePretty(tblEl, col);

  tblEl..display = oldDsply;

  return false;
}

if (document.ELEMENT_NODE == null) {
  document.ELEMENT_NODE = 1;
  document.TEXT_NODE = 3;
}

function getTextValue(el) {

  var i;
  var s;

  s = "";
  for (i = 0; i < el.childNodes.length; i++)
    if (el.childNodes[i].nodeType == document.TEXT_NODE)
      s += el.childNodes[i].nodeValue;
    else if (el.childNodes[i].nodeType == document.ELEMENT_NODE &&
             el.childNodes[i].tagName == "BR")
      s += " ";
    else
      s += getTextValue(el.childNodes[i]);

  return normalizeString(s);
}

function compareValues(v1, v2) {

  var f1, f2;

  f1 = parseFloat(v1.replace(new RegExp("\\s", "g"), ""));
  f2 = parseFloat(v2.replace(new RegExp("\\s", "g"), ""));

  if (!isNaN(f1) && !isNaN(f2)) {
    v1 = f1;
    v2 = f2;
  }

  if (v1 == v2)
    return 0;
  if (v1 > v2)
    return 1
  return -1;
}

var whtSpEnds = new RegExp("^\\s*|\\s*$", "g");
var whtSpMult = new RegExp("\\s\\s+", "g");

function normalizeString(s) {

  s = s.replace(whtSpMult, " ");  // Collapse any multiple whites space.
  s = s.replace(whtSpEnds, "");   // Remove leading or trailing white space.
  s = s.replace(/%/, "");

  return s;
}

var rowClsNm = "alternate_row";
var colClsNm = "sortedColumn";

var rowTest = new RegExp(rowClsNm, "gi");
var colTest = new RegExp(colClsNm, "gi");

function makePretty(tblEl, col) {

  var i, j;
  var rowEl, cellEl;

  for (i = 0; i < tblEl.rows.length; i++) {
   rowEl = tblEl.rows[i];
   rowEl.className = rowEl.className.replace(rowTest, "");
    if (i % 2 != 0)
      rowEl.className += " " + rowClsNm;
    rowEl.className = normalizeString(rowEl.className);
    
	for (j = 0; j < tblEl.rows[i].cells.length; j++) {
      cellEl = rowEl.cells[j];
      cellEl.className = cellEl.className.replace(colTest, "");
      if (j == col)
        cellEl.className += " " + colClsNm;
      cellEl.className = normalizeString(cellEl.className);
    }
  }

  var el = tblEl.parentNode.tHead;
  rowEl = el.rows[el.rows.length - 1];
  
  for (i = 2; i < rowEl.cells.length; i++) {
    cellEl = rowEl.cells[i];
    cellEl.className = cellEl.className.replace(colTest, "");
    
	if (i == col)
      cellEl.className += " " + colClsNm;
      cellEl.className = normalizeString(cellEl.className);
  }
}