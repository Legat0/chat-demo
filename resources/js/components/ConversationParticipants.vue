<template>
  <ul class="chat">
    <li class="left clearfix" v-for="(user, index) in participants" :key="index">
      <div class="chat-body clearfix">
        <div class="header">
          <strong class="primary-font">{{ user.name }}: {{getStringStatus(user.status_id)}}</strong>
        </div>
      </div>
    </li>
  </ul>
</template>

<script>
export default {
  props: ["conversation"],

  data: () => ({
    participants: []
  }),

  methods: {
    getStringStatus(status_id){
      var status_text = "";
      switch(status_id){
       case 0: status_text="offline"; break;
       case 1: status_text="online"; break;
       default: status_text="undefined";  break;
      }
      return status_text;
    },
    getParticipants(conversationId) {
      axios.get(`/conversations/${conversationId}/users`).then(response => {
        this.participants = response.data;
      });
    },
    enablePusher() {
      let pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
        cluster: process.env.MIX_PUSHER_APP_CLUSTER
      });

      let channel = pusher.subscribe(
        `UserEvents`
      );
      channel.bind("App\\Events\\ChangeUserStatus", data => {
        var user = data.user;
        var index = this.participants.findIndex((x)=>{return x.id === user.id})
        Vue.set(this.participants, index, user );
        
      });
    },
  },
  

  created() {
    this.getParticipants(this.conversation);
    this.enablePusher();
  }
};
</script>