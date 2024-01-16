var parent_id = {

    getInput: function(item, value = ''){

        return templates.getInputTmpl({
            type: 'text',
            name: item.name,
            changeaction: 'helpers.simpleaction',
            value: value,
            placeholder: 'Введите '+item.title.toLowerCase(),
            title: item.title
        })

    },

    getOutput: function(value, item_id = false, field_id = false){
        
        if (!value) return ''
        container = templates.getSelectOutputTmpl(value, item_id, field_id)
        
        return container.html()

    },

    callback: function(field, field_html){
        return true
    }

}