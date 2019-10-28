function resize(which, max) {
  var elem = document.getElementById(which);
  if (elem == undefined || elem == null) return false;
  if (max == undefined) max = 100;
  if (elem.width > elem.height) {
    if (elem.width > max) elem.width = max;
  } else {
    if (elem.height > max) elem.height = max;
  }
}// JavaScript Document