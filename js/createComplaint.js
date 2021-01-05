class Model {
  
    constructor() {

    }

}
  
class View {
  constructor() {
    this.app = this.getElement('#root')

    // The title of the app
    this.title = this.createElement('h1')
    this.title.textContent = 'Beschwerde erstellen'
    this.list = this.createElement('ul', 'list')
    this.app.append(this.title, this.list);
  }
  // Create an element with an optional CSS class
  createElement(tag, className) {
    const element = document.createElement(tag)
    if (className) element.classList.add(className)
      return element
    }

  // Retrieve an element from the DOM
  getElement(selector) {
    const element = document.querySelector(selector);
    return element;
  }

  displayForm(){
    console.log("displayed");

    const form = document.getElementById('formular');
    form.style.top = '15%';
    
    const field = document.getElementById('field');
    field.style.width = '100%';

    const subject = document.getElementById('subject')         ;
    subject.style.width = '80%';
    subject.style.margin = '0.5em';
   
    const object = document.getElementById('object')         ;
    object.style.width = '80%';
    object.style.margin = '0.5em';
   
   
    const submit = document.getElementById('submit')         ;
    submit.style.width = '60%';
    submit.style.margin = '0.5em';
    submit.style.float = 'center';
  
 } 
}
class Controller {
  constructor(model, view) {
    this.model = model;
    this.view = view;
    this.view.displayForm();
   
  }
}

const app = new Controller(new Model(),new View() );
         
         
  


 