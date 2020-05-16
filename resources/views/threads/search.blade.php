@extends('threads._layout')

@section('breadcrumbs')
    <div class="d-flex justify-content-between mb-3">
        <ais-breadcrumb :attributes="[
            'thread.channel.parent',
            'thread.channel.name',
        ]">
            <template #default="{ items, refine, createURL }">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent pl-0 pt-0 mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('threads.index') }}">
                                Forum
                            </a>
                        </li>

                        <li class="breadcrumb-item" v-for="(item, index) in items" :key="item.label">
                            <a v-if="item.value"
                               :href="createURL(item.value)"
                               @click.prevent="refine(item.value)"
                               :aria-current="index == items.length - 1 ? 'page' : false"
                               v-text="item.label"></a>
                            <span v-else v-text="item.label"></span>
                        </li>

                        <li class="breadcrumb-item">
                            <span>Recherche</span>
                        </li>
                    </ol>
                </nav>
            </template>
        </ais-breadcrumb>

        <ais-powered-by></ais-powered-by>
    </div>
@endsection

@section('main')
    <ais-hits>
        <template #default="{ items }">
            <thread-result v-for="posts in items" :key="posts.objectID" :data="posts"></thread-result>

            <div v-if="! items.length">
                @include('threads._no-results')
            </div>
        </template>
    </ais-hits>

    <ais-pagination @page-change="onPageChange">
        <template #default="{ currentRefinement, nbPages, pages, isFirstPage, isLastPage, refine, createURL }">
            <ul class="pagination justify-content-center">
                <li :class="['page-item', isFirstPage ? 'disabled' : '']">
                    <a class="page-link"
                       :href="createURL(currentRefinement - 1)"
                       @click.prevent="refine(currentRefinement - 1)">
                        ‹
                    </a>
                </li>
                <li class="page-item" v-for="page in pages" :key="page">
                    <a :class="['page-link', page === currentRefinement ? 'active' : '']"
                       :href="createURL(page)"
                       @click.prevent="refine(page)"
                       v-text="page + 1">
                    </a>
                </li>
                <li :class="['page-item', isLastPage ? 'disabled' : '']">
                    <a class="page-link"
                       :href="createURL(currentRefinement + 1)"
                       @click.prevent="refine(currentRefinement + 1)">
                        ›
                    </a>
                </li>
            </ul>
        </template>
    </ais-pagination>
@endsection
