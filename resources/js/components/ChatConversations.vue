<template>
  <div>
    <h3>Conversations</h3>
    <hr>
    <button class="btn btn-primary btn-sm" @click="createConversation()">New Conversation</button>
    <br>
    <br>
    <ul class="conversation">
      <li class="left clearfix" v-for="(convo, index) in conversations" :key="index">
        <div class="chat-body clearfix">
          <div class="header">
            <a href="#" @click="showConversation(convo.id)">
              <strong class="primary-font">{{ convo.name }}</strong>
            </a> |
            <a
              v-if="!isParticipant(convo.id)"
              href="#"
              class="text-success"
              @click="joinConversation(convo.id)"
            >
              <strong>Join</strong>
            </a>
            <a
              v-else
              href="#"
              class="text-danger"
              title="leave conversation"
              @click="leaveConversation(convo.id)"
            >
              <strong>Leave</strong>
            </a>
            <a              
              href="#"
              class="text-info"
              title="rename conversation"
              @click="renameConversation(index, convo)"
            >
              <strong>Rename</strong>
            </a>
            <a              
              href="#"
              class="text-danger"
              title="delete conversation"
              @click="deleteConversation(index,convo.id)"
            >
              <strong>Delete</strong>
            </a>
          </div>
          <p></p>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data: () => ({        
    conversation: null,
    conversations: []
  }),

  props: ["messages"],

  methods: {
    getNewConvName(prevName){
      var name =  prompt('Name conversation', prevName);
      return name;
    },
    createConversation() {
      var newConversationName = this.getNewConvName('New conversation');
      
      if (newConversationName !== null && newConversationName !== "") {
        axios.post("/conversations", {name: newConversationName }).then(response => {          
          location.reload();          
        });
      }
      
    },
    renameConversation(index, convo) {
      var newConversationName  = this.getNewConvName(convo.name);
      
      if (newConversationName !== null && newConversationName !== "") {
        axios.post("/conversations", {'name': newConversationName, 'id': convo.id }).then(response => {
          Vue.set(this.conversations,index, response.data);

          //location.reload();          
        });
      }
      
    },

    fetchConversations() {
      axios.get("/conversations").then(response => {
        this.conversations = response.data;
      });
    },

    showConversation(id) {
      window.location.href = "home?conversation_id=" + id;
    },

    isParticipant(id) {
      return window.conversations.indexOf(id) !== -1;
    },

    leaveConversation(id) {
      axios.delete(`/conversations/${id}/users`).then(response => {
        window.location.href = "home?conversation_id=" + id;
      });
    },
    deleteConversation(index, id) {
      axios.delete(`/conversations/${id}/delete`).then(response => {
        window.location.href = "home";  
      });
    },

    joinConversation(id) {
      axios.post(`/conversations/${id}/users`).then(response => {
        window.location.href = "home?conversation_id=" + id;
      });
    }
  },

  created() {
    this.fetchConversations();
  }
};
</script>