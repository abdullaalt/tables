var text = {

    getOutput: function(text, item_id = false, field_id = false){
        
        container = templates.getVarcharTmpl(text)

        return container.html()

    },

    getInputValue: function(value){
        return value
    },

    callback: function(field, field_html){
        return true
    }

}