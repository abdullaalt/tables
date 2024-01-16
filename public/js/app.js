var app = {

  models: null,
  btns: false,
  content: false,
  main: false,

  init: function(){
    modals.hideOutsideClick()
    this.btns = $('.buttons-container')
    this.content = $('.content-container')
    this.main = $('.content')
    this.menu = $('ul.menu')
console.log($.cookie('tokens'));
    if (!auth.token && $.cookie('token') == null){
      auth.init()
    }else{
      auth.token = $.cookie('token')
      xhr.sendRequest('/models', 'fillModels', null, app)
    }
    
  },

  registerScroll: function(){
    // this.content.off('scroll')
    // console.log(app.content.children('table').height())
    // this.content.scroll(function(){
    //   console.log($(this).scrollHeight());
    //   if ($(this).scrollTop() > app.content.children('table').height()-300){
    //     console.log($(this).scrollTop());
    //   }
    // })
  },

  fillModels: function(s, data, el){
    models.models = data.data
    content.current_model = models.models[0]
    models.current_model = models.models[0]
    content.init()
    menu.render()
  },

  getUniqueClass: function(tag){

    return tag+(new Date()).valueOf()

  },

  change: function(){
    //$('.checkselect').checkselect();
    $('.js-select2').select2({
      maximumSelectionLength: 100,
      language: "ru"
    });
  },

  getModelByName: function(model_name){

    obj = false

    models.models.forEach(element => {
      if (element.name == model_name){
        obj = element
      }
    });

    return obj

  }

}