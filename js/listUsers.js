class Model {
  
    constructor() {

    }

    getData(){
      var div = document.getElementById("dom-target");
      //console.log(div.textContent);
      var data = JSON.parse(div.textContent);
      
      console.log(data);
      return data;
    }
}
  
class View {
  constructor() {
    this.app = this.getElement('#root')

    // The title of the app
    this.headline = this.createElement('h1');
    this.headline.textContent = 'Userlist';
    this.title = this.createElement('h2')
    this.commandList = this.createElement('ul', 'relative')
    this.app.append(this.headline, this.commandList);
   
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
    if (data.length === 0) {
      const p = this.createElement('p');
      p.textContent = 'No Data passed!';
      this.commandList.append(p);
    } else {
      data.forEach(value => {
        console.log('value: '+value.name)
        this.li = this.createElement('li');
        this.li.style.background = '#f7ddba';
        const span = this.createElement('span');
        span.textContent = value.name;
        this.li.style.float = 'left';         
        this.li.style.width = '60%';
        this.li.append(span);        
        this.commandList.append(this.li); 
        
      })
    }
 }
 openContextMenu(){
  
    this.commandList.addEventListener('contextmenu', event => {
     
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

      this.name = event.target.textContent;
      const update = document.getElementsByClassName('span_1')[0];
      update.addEventListener('click', event => {
        event.preventDefault();

        console.log('update fired '+ this.name);
        var baseURL = "http://localhost/contentFreigabe/";
        var url  = baseURL+ "admin/getUser"
        var params = { 
          name: this.name,
         };
        var posting = $.post(url, params);
        posting.done( function(data){
                          console.log(data);
                          setTimeout(function(){
                            document.location.href = "getUser";
                          },500);
                  });
      });

      const add = document.getElementsByClassName('span_2')[0];
      add.addEventListener('click', event => {
        event.preventDefault();
        setTimeout(function(){
              document.location.href = "addUser";
        },500);
      });
      
      const del =  document.getElementsByClassName('span_3')[0];
      del.addEventListener('click', event => {
        event.preventDefault();
        console.log('delete fired '+ this.name);
        var baseURL = "http://localhost/contentFreigabe/";
        var url  = baseURL+ "admin/deleteUser"
        var params = { 
          name: this.name,
         };
    
        if(confirm("You really want to delete user?") !== false){
          var posting = $.post(url, params);
          posting.done( function(data){
                            console.log(data);
                            setTimeout(function(){
                              document.location.href = "deleteUser";
                            },500);
                    });
        } 
        if(confirm("You really want to delete user?") === false){
          return;
        }
       
     });
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
    const li_1 = this.createElement('li');
    const span_1 = this.createElement('span', 'span_1');
    span_1.textContent= 'Update';
    span_1.style.boxSizing = 'border-box';
    span_1.style.borderBottom = '1px solid grey';
    li_1.append(span_1);
    li_1.style.margin = 0;
    li_1.style.background = '#e5f2fc';
    

    const li_2 = this.createElement('li');
    const span_2 = this.createElement('span', 'span_2');
    span_2.textContent= 'Add User';
    span_2.style.boxSizing = 'border-box';
    span_2.style.borderBottom = '1px solid grey';
    li_2.append(span_2);
    li_2.style.margin = 0;
    li_2.style.background = '#e5f2fc';
    
    const li_3 = this.createElement('li');
    const span_3 = this.createElement('span', 'span_3');
    span_3.textContent= 'Delete';
    span_2.style.boxSizing = 'border-box';
   
   
    li_3.append(span_3);
    li_3.style.background = '#e5f2fc';

    
    ul.append(li_1, li_2, li_3);
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
    this.view.openContextMenu();
  }
}

const app = new Controller(new Model(),new View() );
         
         
  


 