window.addEventListener("beforeunload", function(event) {
  console.log("UNLOAD:1");
  //event.preventDefault();
  event.returnValue = null; //"Any text"; //true; //false;
  //return null; //"Any text"; //true; //false;
});
	