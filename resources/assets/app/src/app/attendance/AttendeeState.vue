<template>
    <StatusPill
        :text="attendee.state"
        :color="color"
        @click="state.showAttendanceForm = true"
    />
    <SlideOver title="Update attendance"
               :open="state.showAttendanceForm"
               @close="state.showAttendanceForm = false"
               width-class="w-screen max-w-2xl">
        <div class="sm:overflow-hidden">
            <div class="bg-white space-y-6">
                <InputButton label="Accept" class="w-full" @click="updateAttendance('accepted')" :disabled="state.isLoading" />
                <InputButton color="gray" label="Maybe" class="w-full" @click="updateAttendance('maybe')" :disabled="state.isLoading" />
                <InputButton color="red" label="Decline" class="w-full" @click="updateAttendance('declined')" :disabled="state.isLoading" />
            </div>
        </div>
    </SlideOver>
</template>
<script setup>

import StatusPill from '@/framework/components/common/pills/StatusPill.vue';
import {computed, reactive} from 'vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';
import SlideOver from '@/framework/components/common/modals/SlideOver.vue';
import {updateAttendanceState} from '@/app/attendance/attendance.api.js';

const emit = defineEmits(['onChange']);

const props = defineProps({
    endpoint: {
        type: String,
        required: true,
    },
    attendable: {
        type: Object,
        required: true,
    },
    attendee: {
        type: Object,
        required: true,
    },
});

const state = reactive({
    isLoading: false,
    showAttendanceForm: false,
});

const color = computed(() => {
    const colors = {
        pending: 'gray',
        declined: 'red',
        accepted: 'green',
    };
    return colors[props.attendee.state] ?? 'green';
});

const updateAttendance = async attendanceState => {
    state.isLoading = true;
    await updateAttendanceState(props.endpoint, attendanceState);
    emit('onChange', props.attendee, attendanceState);
    state.showAttendanceForm = false;
    state.isLoading = false;
};

</script>
