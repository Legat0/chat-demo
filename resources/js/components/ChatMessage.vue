<template>
    <div class="chat-body clearfix">
          <div class="header">
            <strong class="primary-font">{{message.created_at}}: {{ message.sender.name }} </strong>
          </div>
          <p>{{ message.body }}</p>    
          <div  v-for="(file, index) in files" :key="index">            
              <file-component  v-bind:file="file"></file-component >           
            </div>      
          
        </div>
</template>

<script>
    export default {
  props: ["message"],

  data: () => ({
    files: [],

  }),
 computed:{
   
 },
  methods: {
   
    fetchFiles() {
      if (this.message.attachments){
        this.files = this.message.attachments;
      } else {
        axios.get(`/messages/${this.message.id}/files`).then(response => {
          this.files = response.data;
        });

      }
       
      /*
      */
    },
    
  }, 

  created() {
    this.fetchFiles();    
  }
};
</script>