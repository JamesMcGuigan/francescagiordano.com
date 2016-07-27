tableChildNodes = function(node) {
  nodes = array();
  nodes[nodes.length] = node;
  if (node.nodeName == "TABLE"
  ||  node.nodeName == "TR"
  ||  node.nodeName == "TD") {
    for (i=0; i<node.childNodes.length; i++) {
      subnodes = tableChildNodes(node);
      for (j=0; j<subnodes.length; j++) { nodes[nodes.length] = subnodes[j]; }
    }
  } else {
    for (i=0; i<node.childNodes.length; i++) {
      if (node.childNodes[i].nodeName == "TABLE") {
        subnodes = tableChildNodes(node);
        for (j=0; j<subnodes.length; j++) { nodes[nodes.length] = subnodes[j]; }
      } else {
        nodes[nodes.length] = node;
      }
    }
  }
}

startList = function() {
//  document.HTML.width = '99%';
//   document.getElementById("photobike-outer").style.width = '500px';
//   document.getElementById("content").style.border = 'thin solid red';
//   document.getElementById("photobike-outer").style.border = 'thin solid blue';
if (document.all && document.getElementById) {
  navRoot = document.getElementById("dropout");
  for (i=0; i<navRoot.childNodes.length; i++) {
    node = navRoot.childNodes[i];
    if (node.nodeName=="TBODY") {
      for(itr=0; itr<node.childNodes.length; itr++) {
        tr = node.childNodes[itr];
        for(itd=0; itd<tr.childNodes.length; itd++) {
          td = tr.childNodes[itd];
          assignMouseover(td);
//           for (j=0; j<td.childNodes.length; j++) { assignMouseover(td.childNodes[j]); }
        }
      }
    } else {
      assignMouseover(node);
    }
  }
}}

assignMouseover = function(node) {
  if (node.nodeName=="TD" || node.nodeName=="LI" || node.nodeName=="A") {
    node.onmouseover=function() { this.className+=" over"; }
    node.onmouseout =function() { this.className=this.className.replace(" over",""); }
  }
}

window.onload=startList;