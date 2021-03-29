<template>
    <div>
        <h1>{{ __('messages.addbook') }}</h1>
        <form @submit.prevent="submit">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">{{ __('messages.barcode') }} (ISBN)</label>
                <div class="col-lg-10">
                    <input id="barcode" 
                        v-model="barcode" 
                        @keypress.enter.prevent="fetchInfoFromISBN"
                        name="barcode"
                        type="text" 
                        class="form-control" 
                        :class="{ 'is-invalid' :  errors.barcode }"
                        required
                        >
                    <div v-if="errors.barcode" class="invalid-feedback">
                        <template v-for="error_message in errors.barcode" >{{ error_message }}</template>
                    </div>
                    <label v-if="!fetchingIsbnInfo" 
                        :class="isbnInfoClass"
                        >{{ isbnInfoMessage }}</label>
                    
                    <b-spinner v-if="fetchingIsbnInfo" small label="Spinning"></b-spinner>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">{{ __('messages.bookname') }}</label>
                <div class="col-lg-10">
                    <input 
                        v-model="name"
                        name="name" 
                        type="text" 
                        class="form-control" 
                        required
                        :class="{ 'is-invalid' :  errors.name }"
                        >
                    <div v-if="errors.name" class="invalid-feedback">
                        <template v-for="error_message in errors.name" >{{ error_message }}</template>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">{{ __('messages.authorname') }}</label>
                <div class="col-lg-10">
                    <input
                        v-model="author_name"
                        name="author_name" 
                        type="text" 
                        class="form-control" 
                        :class="{ 'is-invalid' :  errors.author_name }"
                        required>
                    <div v-if="errors.author_name" class="invalid-feedback">
                        <template v-for="error_message in errors.author_name" >{{ error_message }}</template>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="cost" class="col-lg-2 col-form-label">{{ __('messages.thumbnail') }}</label>
                <div class="col-lg-10">
                    <b-form-checkbox
                        v-if="isbn_thumbnail_url"
                        v-model="use_custom_thumbnail"
                        switch
                        class="mb-1"
                    >{{ __('messages.use-custom-thumbnail') }}
                    </b-form-checkbox>

                    <div v-if="isbn_thumbnail_url && !use_custom_thumbnail">
                        <img class="thumbnail_preview_image" :src="isbn_thumbnail_url" alt="thumbnail-image" />
                    </div>

                    <div class="input-section mb-1" v-show="use_custom_thumbnail">
                        <input
                            ref="thumbnail"
                            v-on:change="handleThumbnailUpload()"
                            type="file" 
                            class="insert-image" 
                            name="image" 
                            accept=".png,.jpg,.jpeg">
                        <small id="fileHelp" class="form-text text-muted">{{ __('messages.acceptfiletypes') }}</small>
                    </div>
                    <div v-if="use_custom_thumbnail && thumbnail_preview_url">
                        <img class="thumbnail_preview_image" :src="thumbnail_preview_url" alt="thumbnail-image" />
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">{{ __('messages.quantity') }}</label>
                <div class="col-lg-10">
                    <input 
                        v-model="quantity" 
                        type="number" 
                        min="0"
                        class="form-control" 
                        :class="{ 'is-invalid' :  errors.quantity }"
                        required
                        >
                    <div v-if="errors.quantity" class="invalid-feedback">
                        <template v-for="error_message in errors.quantity" >{{ error_message }}</template>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">{{ __('messages.level') }}</label>
                <div class="col-lg-10">
                    <select 
                        v-model="level"
                        name="level"
                        class="form-control"
                        :class="{ 'is-invalid' :  errors.level }"
                        >
                        <option 
                            v-for="level of book_levels"
                            :key="level"
                            :value="level">{{ level }}</option>
                    </select>
                    <div v-if="errors.level" class="invalid-feedback">
                        <template v-for="error_message in errors.level" >{{ error_message }}</template>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">{{ __('messages.date') }}</label>
                <div class="col-lg-10">
                    <input 
                        type="date" 
                        class="form-control" 
                        :class="{ 'is-invalid' :  errors.date }"
                        v-model="date" 
                        required
                        >
                    <div v-if="errors.date" class="invalid-feedback">
                        <template v-for="error_message in errors.date" >{{ error_message }}</template>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label"></label>
                <div class="col-lg-10">
                    <button
                        :disabled="saving"
                        type="submit" 
                        class="form-control btn-success"
                        >
                        {{ __('messages.add') }}
                        <b-spinner v-if="saving" small label="Spinning"></b-spinner>
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    props: ['book_levels', 'today'],
    data: function() {
        return {
            barcode: '',
            name: '',
            author_name: '',
            quantity: '',
            level: '',
            date: '',
            saving: false,
            fetchingIsbnInfo: false,
            isbnInfoMessage: '',
            isbnInfoClass: '',
            errors: [],
            thumbnail: null,
            thumbnail_preview_url: null,
            isbn_thumbnail_url: '',
            use_custom_thumbnail: true
        }
    },
    mounted: function() {
        this.resetForm()
    },
    methods: {
        resetForm() {
            this.barcode = ''
            this.name = ''
            this.author_name = ''
            this.quantity = 1
            this.level = this.book_levels.length > 0 ? this.book_levels[0] : null
            this.date = this.today
            this.thumbnail = null
            this.$refs.thumbnail.value = ''
            this.thumbnail_preview_url = null
            this.isbn_thumbnail_url = ''
            this.use_custom_thumbnail = true

            this.isbnInfoMessage = __('messages.press-enter-after-entering-isbn-to-fetch-book-details')
            this.isbnInfoClass = ''
            this.errors = []
        },
        handleThumbnailUpload(){
            this.thumbnail = this.$refs.thumbnail.files[0];
            this.thumbnail_preview_url = URL.createObjectURL(this.thumbnail);
        },
        fetchInfoFromISBN() {
            if (this.barcode.length < 10) {
                this.isbnInfoMessage = __('messages.isbn-number-length-should-be-greater-than-or-equal-to-10-digits')
                this.isbnInfoClass = 'text-danger'
                return
            }

            this.fetchingIsbnInfo = true;
            axios.get(route('book.isbn.info', this.barcode).url())
                .then(res => {
                    let data = res.data
                    if(data.status == 1) {
                        this.isbnInfoMessage = __('messages.found-info-for-isbn') + " " + data.isbn
                        this.isbnInfoClass = 'text-success'

                        this.name = data.book_name
                        this.author_name = data.author_name
                        this.isbn_thumbnail_url = data.thumbnail_url
                        if (data.thumbnail_url) {
                            this.use_custom_thumbnail = false
                        } else {
                            this.use_custom_thumbnail = true
                        }
                    } else {
                        this.isbnInfoMessage = data.message || trans('messages.something-went-wrong')
                        this.isbnInfoClass = 'text-danger'
                    }
                    this.fetchingIsbnInfo = false
                }).catch(error => {
                    this.isbnInfoMessage = error.response.data.message || trans('messages.something-went-wrong')
                    this.isbnInfoClass = 'text-danger'
                    this.fetchingIsbnInfo = false
                });
        },
        submit() {
            this.saving = true

            let formData = new FormData();

            if (this.use_custom_thumbnail) {
                if (this.thumbnail) {
                    formData.append('image', this.thumbnail)
                }
            } else {
                formData.append('image_url', this.isbn_thumbnail_url)
            }
            
            formData.append('barcode', this.barcode)
            formData.append('name', this.name)
            formData.append('author_name', this.author_name)
            formData.append('quantity', this.quantity)
            formData.append('level', this.level)
            formData.append('date', this.date)

            axios.post(route('book.store').url(), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    let data = res.data
                    if (data.status == 1)
                    {
                        this.showMessage('success', data.message)
                        this.resetForm()
                    }
                    else
                    {
                        this.showError(data.message || trans('messages.something-went-wrong'))
                    }
                    this.saving = false
                }).catch(error => {
                    if(error.response.status == 422)
                    {
                        this.errors = error.response.data.errors
                    }
                    else
                    {
                        this.showError(error.response.data.message || trans('messages.something-went-wrong'))
                    }
                    this.saving = false
                });
        }
    }
}
</script>

<style  scoped>
    .thumbnail_preview_image {
        width: 100px;
        height: 100px;
    }
</style>