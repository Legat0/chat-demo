<template>
  <div>
    <button class="btn btn-danger btn-sm float-right" @click="deleteMessages()">Delete Messages</button>
    <br>
    <br>
    <div v-if="PrevOtherMsgCount >0 ">
      <a href="#" class="text" title="Предыдущие" @click="IsShowOther = !IsShowOther">{{IsShowOther?"-":"+"}} Предыдущие</a>
    <ul clasas="chat" v-show="IsShowOther">
      <li class="left clearfix" v-for="(message, index) in PrevOtherMsg" :key="index">
       <chat-message v-bind:message="message"></chat-message>
      </li>
    </ul>
    </div>
    <div v-if="countPrevWeekMsg(messages.data)>0">
      <a href="#" class="text" title="На прошлой неделе" @click="IsShowWeek = !IsShowWeek">{{IsShowWeek?"-":"+"}} На прошлой неделе</a>
    <ul clasas="chat" v-show="IsShowWeek">
      <li class="left clearfix" v-for="(message, index) in selectPrevWeekMsg(messages.data)" :key="index">
        <chat-message v-bind:message="message"></chat-message>
      </li>
    </ul>
    </div>
    <div v-if="countYesterdayMsg(messages.data)>0">
       <a href="#" class="text" title="Вчера" @click="IsShowYesterday = !IsShowYesterday">{{IsShowYesterday?"-":"+"}} Вчера</a>      
    <ul class="chat" v-show="IsShowYesterday">
      <li class="left clearfix" v-for="(message, index) in selectYesterdayMsg(messages.data)" :key="index">
        <chat-message v-bind:message="message"></chat-message>
      </li>
    </ul>
    </div>
    <div v-if="countTodayMsg(messages.data)>0">
      <a href="#" class="text" title="Сегодня" @click="IsShowToday = !IsShowToday">{{IsShowToday?"-":"+"}} Сегодня</a>      
    <ul class="chat" v-show="IsShowToday">
      <li class="left clearfix" v-for="(message, index) in selectTodayMsg(messages.data)" :key="index">
        <chat-message v-bind:message="message"></chat-message>
        
      </li>
    </ul>
    </div>
    
  </div>
</template>

<script>
export default {
  props: ["conversation"],

  data: () => ({
    messages: [],
    IsShowOther: false,
    IsShowWeek: false,
    IsShowYesterday: false, 
    IsShowToday: true
  }),
  
  computed: {
    PrevOtherMsg:  function() {
      if (this.messages && this.messages.data) {
        return this.messages.data.filter(function (message) {
      var endDay = new Date();
      endDay.setDate(endDay.getDate() - 7);
      endDay.setHours(0,0,0);
      
      var created_date =  new Date(message.created_at);
      return (created_date < endDay );
    })

      } else return [];      
    },
    PrevOtherMsgCount:  function(){
       if (this.messages) {
         return this.PrevOtherMsg.length;
       } else return 0;
    }
  },

  methods: {
   selectPrevWeekMsg(messages) {
      if (messages==null) return null;
    return messages.filter(function (message) {
      var startDay = new Date();
      startDay.setDate(startDay.getDate() - 7);
      startDay.setHours(0,0,0);
      var endDay = new Date();
      endDay.setHours(0,0,0);
      endDay.setDate(endDay.getDate() - 1);
      var created_date =  new Date(message.created_at);
      return (created_date > startDay && created_date < endDay);
    })
    },
     countPrevWeekMsg (messages) {
        if (messages==null) {
          return 0;
        } else {
          return this.selectPrevWeekMsg(messages).length;
        }
    },
    selectYesterdayMsg(messages) {
      if (messages==null) return null;
    return messages.filter(function (message) {
      var Yesterday = new Date();
      Yesterday.setDate(Yesterday.getDate() - 1)
      Yesterday.setHours(0,0,0);
      var Today = new Date();
      Today.setHours(0,0,0);
      var created_date =  new Date(message.created_at);
      return (created_date > Yesterday && created_date < Today);
    })
    },
     countYesterdayMsg (messages) {
        if (messages==null) {
          return 0;
        } else {
          return this.selectYesterdayMsg(messages).length;
        }    

    },
    
    selectTodayMsg: function (messages) {
    return messages.filter(function (message) {
      var Today = new Date();
      Today.setHours(0,0,0);
      var created_date =  new Date(message.created_at);      
      return created_date > Today;
    })
    },
    countTodayMsg(messages) {
        if (messages==null) {
          return 0;
        } else {
          return this.selectTodayMsg(messages).length;
        }
    },
    fetchMessages() {     
      axios
        .get(`/conversations/${this.conversation}/messages`)
        .then(response => {
          this.messages = response.data;
        });
    },

    deleteMessages() {
      axios
        .delete(`/conversations/${this.conversation}/messages`)
        .then(response => {
          this.messages = response.data;
        });
    },
    enablePusher() {
      let pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
        cluster: process.env.MIX_PUSHER_APP_CLUSTER
      });

      let channel = pusher.subscribe(
        `mc-chat-conversation.${this.conversation}`
      );
      channel.bind("Musonza\\Chat\\Eventing\\MessageWasSent", data => {
        this.messages.data.push(data.message);
      });
    },
    
  },
  

  created() {
    this.fetchMessages();
    this.enablePusher();
  }
};
</script>