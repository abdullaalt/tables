var modals = {

    have_modal: false,
    is_hide_outside_click: false,
    uc: false,
    callback: false,

    hideOutsideClick: function(){
        document.addEventListener( 'click', (e) => {
            if (!modals.is_hide_outside_click) return true
            el = document.querySelector('.'+modals.uc)
            const withinBoundaries = e.composedPath().includes(el);

            if ( ! withinBoundaries ) {
                if (content.edit_el){
                    content.outFromEditMode()
                }else{
                    el.remove()
                }
                modals.reset()
            }
        })
    },

    reset: function(){
        modals.is_hide_outside_click = false
        modals.uc = false
        modals.have_modal = false
        if (this.callback){
            this.callback()
            this.callback = false
        }
    },

    getModalsContainer: function(content, position, with_bg = true, is_hide_outside_click = true, coords = false){
        el = $('<div></div>')
        el.append(content)
        html = el.html();
        uc = app.getUniqueClass('div_popup')
        if (with_bg){
            item = this.withBgModalContainer(html)
            item.click(function(){
                $(this).remove()
                modals.reset()
            })

            item.find('.popup_content').click(function(e){
                e.stopPropagation()
            })
        }else{
            item = this.modalContainer(html)
            item.addClass(uc)
        }

        if (position == 'center')
            item.addClass('center')
        else if (position == 'manual'){
            item.css({
                'position': 'absolute',
                'left': coords.left,
                'top': coords.top, 
                'width': coords.width
            }).addClass('manual')
        }

        if (is_hide_outside_click){
            setTimeout(function(){
                modals.uc = uc
                modals.is_hide_outside_click = true
            }, 100)
        }

        this.have_modal = true

        return item

    },

    withBgModalContainer: function(content){

        return $('#popupContainerTmpl').tmpl({
            content: content
        })

    },

    modalContainer: function(content){

        return $('#popupContentTmpl').tmpl({
            content: content
        })

    },

    show: function(modal, callback = false){

        $('body').append(modal)

    },

    hide: function(modal){
        modal.remove()
        this.reset()
    }

}