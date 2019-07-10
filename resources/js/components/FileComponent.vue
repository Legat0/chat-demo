<template>
    <img :class="{ full: fullWidthImage }" @click="fullWidthImage = !fullWidthImage" v-if="isImage(file)" :src="filePath(file)" >
     <a v-else :href="filePath(file)">{{file.name}}</a>
</template>

<script>
    export default {
  props: ["file"],

  data: () => ({
    'fullWidthImage': false

  }),
 computed:{
   
 },
  methods: {
    isImage(file){
      return file.ext.match(/(jpg|jpeg|png|gif)$/);
    },
    filePath: function(file){     
     return "..\\storage\\attachments\\" + file.id + "\\" + file.name;
   },
   
  }, 

  created() {
    
  }
};
</script>

<style>
.full {
  width: 100%;
  height: auto;
}
img {
  width: 150px;
  border-radius: 2px;
  box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, 0.5);
  transition: width 1s;
}
img:hover {
  cursor: pointer;
}
</style>
>
