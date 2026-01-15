
// resources/js/layout.js

window.onToggleMenu = function (icon) {
    const navlinks = document.querySelector('.nav-links');
      
    navlinks.classList.remove('hidden')
        
    if( icon.name === 'menu-outline'){
        icon.name = 'close-outline';
        
    }else{
        icon.name = 'menu-outline' 
        navlinks.classList.toggle('hidden')
    }
};
