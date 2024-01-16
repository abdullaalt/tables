var menu = {

    active_line: false,
    menu: false,

    showTrContextMenu: function(el){

    },

    render: function(){

        this.menu = templates.getMenuTmpl(models.models)
        $('.sidebar').append(this.menu)
        this.menu.find('li').eq(1).addClass('active')

    },

    addMenuItem: function(item){
        this.menu.append(templates.getMenuItemTmpl(item))
    },

    navigate: function(model, el){

        $('.active', this.menu).removeClass('active')
        el.parent().addClass('active')
    
        content.current_model = app.getModelByName(model)
        models.current_model = content.current_model
        content.init()
        filter.filters = new Array()
    
      }

}