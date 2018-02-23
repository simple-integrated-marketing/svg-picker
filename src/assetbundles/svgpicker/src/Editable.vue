
<script>
    export default {
        name: "editable",
        template:'<span contenteditable="true" @input="update"></span>',
        props:['content'],
        mounted:function(){
            this.$el.innerText = this.content;
        },
        methods:{
            update:function(event){
                this.$emit('update',event.target.innerText);
            }
        }
    }
</script>
