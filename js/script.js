class Model {
  
    constructor() {

    }

    getData(){
      var div = document.getElementById("dom-target");
      console.log(div.textContent);
      var data = JSON.parse(div.textContent);
      var imgPath = "./images/schoolyard.jpg";
      data.push(imgPath);
      console.log(data);
      return data;
    }
}
  
class View {
  constructor() {
    this.app = this.getElement('#root')

    // The title of the app
    this.div1 = this.createElement('div');
    this.title = this.createElement('h2');
    this.title.style.position = 'relative';
    this.title.textContent = 'Schoolyard';
    this.div1.style.float = 'left';
    this.div1.append(this.title);
    this.list = this.createElement('ul', 'list')
    this.list.append(this.div1);
    this.app.append(this.list);
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

  displayData(data){
    console.log("displayed", data);
    const div = this.createElement('div', 'videos');


    if (data.length === 0) {
      const p = this.createElement('p');
      p.textContent = 'No videos in Fileystem!';
      this.list.append(p);
    } else {
      data.forEach(value => {
        const li = this.createElement('li');
        li.style.width = '40%';
        li.style.float = 'right';
        if(value.endsWith('jpg')){
        
          console.log("true "+value);
          const img = this.createElement('img',  'image');
          img.src = value;
          img.style.alignItems = 'left';
          img.style.display = 'flex';
          img.style.felxDirection = 'column';
          img.style.margin = '2,5%';
          img.style.padding = '0%';
          img.style.width = '100%';
          
          this.list.append(img);
        } 
      
         if(value.endsWith('jpg') == false){
          this.div2 = this.createElement('div');
          this.div2.style.width = '40%';
          this.div2.style.float = 'right';
          const span = this.createElement('span');
          span.textContent = value;
          
          li.append(span);        
          this.div2.append(li);
          this.list.append(this.div2);

        }
      })
    }
 }

 dropContextMenu(){
  this.list.addEventListener('contextmenu', event => {
     
    var cmenu = true;
    this.contextmenue(cmenu, event);
    event.preventDefault();
    console.log("fired " + event.target.textContent);
    
    document.addEventListener('mousedown', event  => {
      event.preventDefault(); 
      var button = event.button;
      if ( button === 1){
        console.log("button clicked");
        this.hideContextMenu();
      }
     
     })

    document.addEventListener('keydown', event  => {
      if (event.code === 'Escape') {
          console.log("keybord clicked "+ event.code);
          this.hideContextMenu();
          console.log("menu hidden");
      }
    
    });

    const login = document.getElementsByClassName('span_0')[0];
    login.addEventListener('click', event => {
      event.preventDefault();
      setTimeout(function(){
        document.location.href = "http://localhost/schoolyard/index/formular"
      },500);
    })

   
  }, false);
}

hideContextMenu(){
  this.div.remove();
}

getPosition(e) {
  var posx = 0;
  var posy = 0;

  if (!e) var e = window.event;
   
    posx = e.clientX ;
                       
    posy = e.clientY ; 
 
  console.log('position handled');
  return {
    x: posx,
    y: posy
  }
}


contextmenue(cmenu,e){
  
  //build the dom-elements here:
if(!cmenu || cmenu === null) return true;

  this.div = this.createElement('div', 'absolute');
  this.div.setAttribute("id", "context");

  const ul = this.createElement('ul');
  const li = this.createElement('li');
  const span_0 = this.createElement('span', 'span_0');
  span_0.textContent= 'Login';
  li.append(span_0);
  li.style.margin = 0;
  li.style.background = '#fff2df';
  li.style.borderBottom = '1px solid #dd0074';

  
  ul.append(li);
  this.div.append(ul);
  

  var menuPosition = this.getPosition(e);
  console.log(menuPosition);
  document.body.append(this.div);
  this.div.style.position = 'absolute';
  this.div.style.left = menuPosition.x + "px";
  this.div.style.top = menuPosition.y + "px";

  return false;
 }
}
class Controller {
  constructor(model, view) {
    this.model = model;
    this.view = view;
    var data = this.model.getData();
    this.view.displayData(data);
    this.view.dropContextMenu();
  }
}

const app = new Controller(new Model(),new View() );
         
         
  


 