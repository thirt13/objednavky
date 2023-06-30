document.addEventListener("DOMContentLoaded", () => {
    naja.initialize()
})

naja.uiHandler.addEventListener("interaction", (event) => {
	const {element} = event.detail;
	const question = element.dataset.confirm;
	if (question && ! window.confirm(question)) {
		event.preventDefault();
	}
})



/**
 * company searching input
 * */
let search = document.querySelector("#search")

if (search) {
    search.addEventListener("keyup", (e) => {
        if(search.value.length >= 1){
            naja.makeRequest('GET', "?do=search", {search: search.value})
        } 
        else{
            naja.makeRequest('GET', "?do=search", {search: ""})
        }
      
    });

}


const openForm = () => {
    document.querySelector("#myForm").style.width = "71vw";
}
  
const closeForm = () => {
    document.querySelector("#myForm").style.width = "0vw";
}

naja.addEventListener("success", (event) => {
    //const closing = event.detail.payload.closeForm
    if (event.detail.payload.closeForm === true) {
        document.querySelector("#myForm").style.width = "0vw";
       
    } 
})