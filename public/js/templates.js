var templates = {

    getLoader: function(){
        return $('#loaderTmpl').tmpl()
    },
	
    getInputTmpl: function(data){

        return $('#inputTmpl').tmpl(data)

    },

    getButton: function(data){

        return $('#btnTmpl').tmpl(data)

    },

    getAddModelTmpl: function(){
        return $('#addModelTmpl').tmpl()
    },

    getMenuTmpl: function(items){
        return $('#menuTmpl').tmpl({items: items})
    },

    getMenuItemTmpl: function(item){
        return $('#menuItemTmpl').tmpl({item: item})
    },

    getTables: function(titles){

        return $('#tableTmpl').tmpl({fields: titles})

    },

    fillTableWithData: function(data){
    
        return $('#tableItemsTmpl').tmpl({items: data})

    },

    getFilterPanel: function(data){
    
        return $('#filterTmpl').tmpl()

    },

    getCheckboxOutputTmpl: function(value){
        return $('#checkboxOutputTmpl').tmpl({value: value})
    },

    getAddFormTmpl: function(){
        return $('#addFormTmpl').tmpl()
    },

    getSelectTmpl: function(data){

        return $('#selectTmpl').tmpl(data)

    },

    getVarcharTmpl: function(data){
        return $('#textTmpl').tmpl({text: data})
    },

    getSelectMultipleTmpl: function(data){

        return $('#selectMultipleTmpl').tmpl(data)

    },

    getSelectOptions: function(data){
        return $('#selectOptionsTmpl').tmpl({data: data})
    },

    getSelectOption: function(item){
        return $('#selectOptionTmpl').tmpl({item: item})
    },

    getTableTHTmpl: function(title, is_filter){
        return $('#tableTHTmpl').tmpl({title: title, is_filter: is_filter})
    },

    getTableTDTmpl: function(html){
        return $('#tableTDTmpl').tmpl({html: html})
    },

    getSelectOutputTmpl: function(list, item_id, field_id){
        return $('#selectOutputTmpl').tmpl({list: list, count: list.length, item_id: item_id, field_id: field_id})
    },

    getFieldsControlPanel: function(list = false){
        
        if (!list){
            list = fields.current_model_fields
        }

        return $('#fieldsControlPanelTmpl').tmpl({list: list})

    }

}