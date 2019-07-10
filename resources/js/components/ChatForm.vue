<template>
  <div>
    <div class="input-group" v-if="isParticipant()">
      <input
        id="btn-input"
        type="text"
        name="message"
        class="form-control input-sm"
        placeholder="Type your message here..."
        v-model="newMessage"
        @keyup.enter="sendMessage"
      >
      &nbsp;&nbsp;      
      
     
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="files" ref="files" @change="processFile()">
    <label class="custom-file-label" for="inputGroupFile">{{addFile!=''?addFile.name:"Choose file"}}</label>
  </div>
  &nbsp;&nbsp; 
  <div class="input-group-btn">
        <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">Send</button>
      </div>

    </div>
    <div v-else>
      <button class="btn btn-success btn-sm" @click="joinConversation()">Join Conversation</button>
    </div>
    
  </div>
</template>

<script>
export default {
  props: ["user", "conversation"],

  data() {
    return {
      newMessage: "",
      addFile:''
    };
  },

  methods: {
    processFile(){
      
      if(this.$refs.files.files[0].size>1048576) {
                    alert('File size is larger than 1MB!');
                } else {
                  this.addFile = this.$refs.files.files[0];
                  console.log(this.addFile );
                }
      
    },
    isParticipant() {
      return window.conversations.indexOf(this.conversation) !== -1;
    },

    joinConversation() {
      axios.post(`/conversations/${this.conversation}/users`).then(response => {
        location.reload();
      });
    },

    sendMessage() {

       let formData = new FormData();
       if (this.addFile!=''){
          formData.append('file', this.addFile);

       }
     
        formData.append('message',this.newMessage);

        axios.post(`/conversations/${this.conversation}/messages`, formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }}
        ).then(response => {
          this.newMessage = "";
          this.addFile = '';
          //location.reload(); // comment this out if you are broadcasting
        }).catch(error => {
          console.log(error);
          alert(error.message);
        });
        /*
      axios.post(`/conversations/${this.conversation}/messages`, {
          message: this.newMessage,
          fileToUpload: this.addFiles
        },{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }}
        ).then(response => {
          this.newMessage = "";
          //location.reload(); // comment this out if you are broadcasting
        });*/
    }
  }
};
</script>