<template>
    <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import '@ckeditor/ckeditor5-build-classic/build/translations/en';
import { CKEditor } from '@ckeditor/ckeditor5-vue';

export default {
    name: 'Ckeditor',
    components: {
        ckeditor: CKEditor.component
    },
    props: ['value'],
    data() {
        return {
            editor: ClassicEditor,
            editorData: this.value,
            editorConfig: {
                // The configuration of the editor.
            }
        };
    },
    watch: {
        value(newValue) {
            this.editorData = newValue;
        },
        editorData(newValue) {
            this.$emit('input', newValue);
        }
    }
}
</script>