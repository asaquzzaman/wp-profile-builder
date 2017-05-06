<div>
    <div class="wpup-user-search-box-wrap">
        <label class="wpup-search-box-label" for="search-input"><i class="fa fa-search" aria-hidden="true"></i></label>
        <input class="wpup-user-search-field" @keyup="searchUser()" v-model="search_user" type="text" placeholder="<?php _e('Search Users', 'wpup'); ?>">
        <a v-if="search_cross" class="wpup-search-box-cancel" @click.prevent="searchCross()" href="#"><span class="fa fa-times-circle"></span></a>
        <span v-if="user_loading" class="wpup-search-loading wpup-search-box-cancel wpup-spinner"></span>
    </div>
    <ul class="wpup-members-ul">
       <!--  <li v-if="user_loading">
            <div class="wpup-data-load-before">
                <div class="loadmoreanimation">
                    <div class="load-spinner">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                        <div class="rect4"></div>
                        <div class="rect5"></div>
                    </div>
                </div>
            </div>
        </li> -->
        <li  v-if="!user_loading && !users.length"><?php _e('No Users Found', 'wpup'); ?></li>
        <li v-if="users" class="wpup-members-li" v-for="user in users">
            <a :href="user.data.profile" target="_blank">
                <img class="wpup-members-image" :src="user.data.avatar" height="60" width="60">
            </a>
            
            <div class="wpup-profile-link"><a :href="user.data.profile">{{ user.data.display_name }}</a></div>
            <div class="wpup-biographical-info" v-if="user.data.description" v-html="user.data.description"></div>
            <div class="wpup-biographical-info" v-else><?php _e( 'No biographical information found', 'wpup' ); ?></div>
            <div class="wpup-view-profile">
            
                <router-link  :to="{ name: 'user_profile', params: {user_id: user.data.ID }}"><?php _e( 'View Profile', 'wpup' ); ?></router-link>
            </div>
        </li>
    </ul>

    <wpup-paginaton :pagination_spinner="pagination_spinner" :total="total" :user="search_user" :limit="limit" :page_number="page_number"></wpup-paginaton>
</div>