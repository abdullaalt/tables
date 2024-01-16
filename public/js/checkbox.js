var checkbox = {

    getInput: function(item, value = ''){

        return templates.getInputTmpl({
            type: 'checkbox',
            name: item.name,
            changeaction: 'helpers.simpleaction',
            value: value,
            placeholder: 'Введите '+item.title.toLowerCase(),
            title: item.title
        })

    },

    getOutput: function(text, item_id = false, field_id = false){

        container = templates.getCheckboxOutputTmpl(text)

        return container.html()

    },

    getInputValue: function(value){
        return value
    },

    callback: function(field, field_html){
        if (content.current_item[field.name].value){
            field_html.find('input').prop('checked', true)
        }else{
            field_html.find('input').prop('checked', false)
        }
    }

}