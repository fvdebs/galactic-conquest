(function(){var initializing=!1,fnTest=/xyz/.test(function(){xyz})?/\b_super\b/:/.*/;this.Class=function(){};Class.extend=function(prop){var _super=this.prototype;initializing=!0;var prototype=new this();initializing=!1;for(var name in prop){prototype[name]=typeof prop[name]=="function"&&typeof _super[name]=="function"&&fnTest.test(prop[name])?(function(name,fn){return function(){var tmp=this._super;this._super=_super[name];var ret=fn.apply(this,arguments);this._super=tmp;return ret}})(name,prop[name]):prop[name]}
    function Class(){if(!initializing&&this.init)
        this.init.apply(this,arguments)}
    Class.prototype=prototype;Class.constructor=Class;Class.extend=arguments.callee;return Class}})();

let gc = {};

gc.form = Class.extend({

    /**
     * @return void
     */
    init : function() {
        let that = this;

        $('form').on('submit', function(event) {
            event.preventDefault() ;
            event.stopPropagation();

            let form = $(this);
            form.find('button').addClass('is-loading');

            $.post(form.attr('action'), form.serialize(), function(result) {
                if (result.isSuccess === true) {
                    window.location.href = result.message;
                    return;
                }

                that.handleError(form, result.message);
                form.find('button').removeClass('is-loading');
            }, 'json');
        });
    },

    /**
     * @param {object} form
     * @param {object} messages
     * @return void
     */
    handleError: function(form, messages) {
        let that = this;

        // reset
        form.find('p.help.is-danger').remove();

        let index;
        for (index in messages) {
            that.populateFieldErrors(form, index, messages[index]);
        }
    },

    /**
     * @param {object} form
     * @param {string} name
     * @param {object} errors
     * @return void
     */
    populateFieldErrors : function(form, name, errors) {
        let field = form.find('[name=' + name + ']');
        let fieldParent = field.parents('div.field');

        if (!field.hasClass('is-danger')) {
            field.addClass('is-danger');
        }

        let index, messages = '';
        for (index in errors) {
            if (errors[index] !== '') {
                messages += '<li>' + errors[index] + '</li>';
            }
        }

        if (messages !== '') {
            let helpBlock = $('<p class="help is-danger"></p>');
            helpBlock.html('<ul>' + messages + '</ul>');
            fieldParent.append(helpBlock);
        }
    }
});

$(document).ready(function() {
    new gc.form();
});