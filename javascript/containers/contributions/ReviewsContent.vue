/**
* This is the Reviews Tab component.
*/

<script>
import { mapActions } from 'vuex';
import Pager from '../../components/general/Pager.vue';
import ItemNotificationImageText from '../../components/notification/ItemNotificationImageText.vue';
import store from './store';
import { fullDate } from '../../services/DateFormatter';
import Spinner from '../../components/general/Spinner.vue';
import ContributionsStatistics from '../../components/contributions/ContributionsStatistics.vue';
import ContributionsFilters from '../../components/contributions/ContributionsFilters.vue';
import { createLog } from '../../services/LogsService';

export default {
  name: 'ReviewsContent',
  store,
  props: {
    active: {
      type: Boolean,
      default: false,
      required: true,
    },
  },
  components: {
    ItemNotificationImageText,
    Pager,
    Spinner,
    ContributionsStatistics,
    ContributionsFilters,
  },
  methods: Object.assign(
    {},
    mapActions([
      'getUserSuggestions',
      'getUserSuggestionsStatistics',
      'setSelectedFilter',
    ]),
    {
      handleChangePage(page) {
        this.getUserSuggestions({ page, type: 'reviews' });
      },
      handleSelectFilter(filterItem) {
        this.setSelectedFilter({ filter: filterItem, type: 'reviews' });
      },
      suggestionText(status, text, fieldName, photoName, userName) {
        if (status === null) {
          return `Sua sugestão '${text}' para o campo '${fieldName}' na imagem '${photoName}' foi enviada para a revisão do autor da imagem '${userName}'.`;
        } else if (status === 1) {
          return `Sua sugestão '${text}' para o campo '${fieldName}' na imagem '${photoName}' foi aceita pelo autor da imagem '${userName}'!`;
        } else if (status === 0) {
          return `Sua sugestão '${text}' para o campo '${fieldName}' na imagem '${photoName}' foi recusada pelo autor da imagem '${userName}'.`;
        }
        return '';
      },
      handlePressObtainedPointsLink() {
        // Here we're just logging that we pressed the obtained points link
        createLog(window.location.origin, 'redirect-my-points', {});
      },
    },
  ),
  created() {
    // Getting user suggestions statistics
    this.getUserSuggestionsStatistics({ type: 'reviews' });
    // Getting user suggestions
    this.getUserSuggestions({ page: 1, type: 'reviews' });
  },
  data() {
    return {
      store,
      fullDate,
    };
  },
};
</script>

<template>
  <div
    class="tab"
    v-bind:class="{ active: active }"
  >
    <div v-if="store.state.reviewsSuggestionsStatistics !== null" class="statistics-container">
      <ContributionsStatistics
        title="Suas Revisões"
        :acceptedSuggestions="store.state.reviewsSuggestionsStatistics.accepted"
        :waitingSuggestions="store.state.reviewsSuggestionsStatistics.waiting"
        :rejectedSuggestions="store.state.reviewsSuggestionsStatistics.rejected"
        :totalSuggestions="store.state.reviewsSuggestionsStatistics.total"
        :showPoints="store.state.isGamefied"
        :obtainedPoints="store.state.reviewsSuggestionsStatistics.points"
        :obtainedPointsLink="`/users/${store.state.currentUser.id}#my_points`"
        :handlePressObtainedPointsLink="handlePressObtainedPointsLink"
      />
    </div>
    <div>
      <ContributionsFilters
        :filterItems="store.state.filterItems"
        :handleSelectFilter="handleSelectFilter"
        :selectedFilter="store.state.selectedFilterReviews"
      />
    </div>
    <div v-if="store.state.isLoadingSuggestions">
      <Spinner />
    </div>
    <div v-if="!store.state.isLoadingReviewsSuggestions && store.state.userReviewsSuggestions.length > 0">
      <ul>
        <ItemNotificationImageText
          v-for="suggestion in store.state.userReviewsSuggestions"
          v-bind:key="suggestion.id"
          :imageURL="`/arquigrafia-images/${suggestion.photo.id}_home.jpg`"
          :text="suggestionText(suggestion.accepted, suggestion.text, suggestion.field.name, suggestion.photo.name, suggestion.photo.user.name)"
          :date="fullDate(suggestion.updated_at)"
          :clickableURL="`/photos/${suggestion.photo.id}`"
        />
      </ul>
      <Pager
        :currentPage="store.state.reviewsCurrentSuggestionsPage"
        :numPages="store.state.reviewsTotalNumSuggestionPages"
        :handleChangePage="handleChangePage"
      />
    </div>
    <div v-if="!store.state.isLoadingReviewsSuggestions && store.state.userReviewsSuggestions.length === 0">
      <p>Não foi encontrado nenhuma revisão.</p>
    </div>
  </div>
</template>

<style scoped>
  .statistics-container {
    margin-top: 10px;
    margin-bottom: 10px;
  }
</style>
