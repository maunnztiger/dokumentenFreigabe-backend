class Model {
  
    constructor() {

    }

    getData(){
      var div = document.getElementById("dom-target");
      console.log(div.textContent);
      var data = JSON.parse(div.textContent);
      console.log(data);
      return data;
    }
}
  
class View {
  constructor() {
    this.app = this.getElement('#root')

    // The title of the app
    this.title = this.createElement('h1')
    this.title.textContent = 'Startseite'
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
       
        if(value.endsWith('jpg')){
        
          console.log("true "+value);
          const img = this.createElement('img',  'image');
          img.src = value;
          img.style.alignItems = 'left';
          img.style.display = 'flex';
          img.style.felxDirection = 'column';
          img.style.margin = '2,5%';
          img.style.padding = '2%';
          img.style.width = '20%';
          
          this.videoList.append(img);
        } 
      
         if(value.endsWith('jpg') == false){
          const span = this.createElement('span');
          span.textContent = value;
          li.style.float = 'left';         
          li.style.width = '60%';
          li.append(span);        
          this.list.append(li); 
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
        document.location.href = "http://localhost/ISPComplaintsCRM/index/formular"
      },500);
    })

    const userList = document.getElementsByClassName('span_1')[0];
    userList.addEventListener('click', event => {
      event.preventDefault();
      setTimeout(function(){
        document.location.href = "http://localhost/ISPComplaintsCRM/index/createComplaint";
      },500);
    });

    /*const videolist = document.getElementsByClassName('span_2')[0];
    videolist.addEventListener('click', event => {
      event.preventDefault();
      setTimeout(function(){
        document.location.href = "listVideoNames";
      },500);
    });
    
    const logout =  document.getElementsByClassName('span_3')[0];
    logout.addEventListener('click', event => {
      event.preventDefault();
      console.log('delete fired '+ this.name);
      setTimeout(function(){
            document.location.href = "http://localhost/contentFreigabe/index/logout";
          },500);
     });*/
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
  const li_0 = this.createElement('li');
  const span_0 = this.createElement('span', 'span_0');
  span_0.textContent= 'Login';
  li_0.append(span_0);
  li_0.style.margin = 0;
  li_0.style.background = '#fff2df';
  li_0.style.borderBottom = '1px solid #dd0074';


  const li_1 = this.createElement('li');
  const span_1 = this.createElement('span', 'span_1');
  span_1.textContent= 'Create Complaint';
  li_1.append(span_1);
  li_1.style.margin = 0;
  li_1.style.background = '#fff2df';
  li_1.style.borderBottom = '1px solid #dd0074';

  /*const li_2 = this.createElement('li');
  const span_2 = this.createElement('span', 'span_2');
  span_2.textContent= 'Videos';
  li_2.append(span_2);
  li_2.style.margin = 0;
  li_2.style.background = '#fff2df';
  li_2.style.borderBottom = '1px solid #dd0074';

  const li_3 = this.createElement('li');
  const span_3 = this.createElement('span', 'span_3');
  span_3.textContent= 'Abmelden';
  li_3.style.background = '#fff2df';
  li_3.append(span_3);*/
  
  ul.append(li_0, li_1);
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
         
         
  


 