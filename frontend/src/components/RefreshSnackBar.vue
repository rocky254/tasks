<template>
  <div>
    <v-snackbar 
      bottom 
      right 
      :value="updateExists" 
      :timeout="0" 
      color="secondary"
    >
      An Update is Available
      <v-btn @click="refreshApp" color="primary">
        Click to Refresh
      </v-btn>
    </v-snackbar>
  </div>
</template>

<script>
import update from '../mixins/update';

export default {
  name: "RefreshSnackBar",
  mixins: [update],
};
</script>
