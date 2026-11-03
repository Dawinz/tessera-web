<div class="col-xxl-3 col-xl-12 col-lg-12">
    <div id="route_points_panel" aria-busy="true">
        <div class="card route-points-card">
            <div class="card-header route-points-header">
                <h6 class="route-points-heading mb-1">Departure &amp; Arrival</h6>
                <span class="route-skeleton-shimmer route-points-skeleton-route" aria-hidden="true"></span>
                <span class="visually-hidden">Loading departure and arrival points...</span>
            </div><!-- end card header -->
            <div class="card-body p-0">
                <div class="route-points" role="status">
                    <section class="point-group is-departure is-skeleton" aria-hidden="true">
                        <header class="point-group-head">
                            <span class="point-group-icon"><i class="uil uil-sign-out-alt"></i></span>
                            <span class="route-skeleton-shimmer route-points-skeleton-title"></span>
                            <span class="route-skeleton-shimmer route-points-skeleton-count"></span>
                        </header>
                        <ul class="point-list">
                            <li class="point-item">
                                <span class="point-item-marker"></span>
                                <div class="point-item-body">
                                    <span class="route-skeleton-shimmer route-points-skeleton-name" style="width:72%"></span>
                                    <span class="route-skeleton-shimmer route-points-skeleton-location" style="width:48%"></span>
                                </div>
                                <span class="route-skeleton-shimmer route-points-skeleton-time"></span>
                            </li>
                            <li class="point-item">
                                <span class="point-item-marker"></span>
                                <div class="point-item-body">
                                    <span class="route-skeleton-shimmer route-points-skeleton-name" style="width:64%"></span>
                                    <span class="route-skeleton-shimmer route-points-skeleton-location" style="width:42%"></span>
                                </div>
                                <span class="route-skeleton-shimmer route-points-skeleton-time"></span>
                            </li>
                            <li class="point-item">
                                <span class="point-item-marker"></span>
                                <div class="point-item-body">
                                    <span class="route-skeleton-shimmer route-points-skeleton-name" style="width:58%"></span>
                                    <span class="route-skeleton-shimmer route-points-skeleton-location" style="width:40%"></span>
                                </div>
                                <span class="route-skeleton-shimmer route-points-skeleton-time"></span>
                            </li>
                        </ul>
                    </section>

                    <section class="point-group is-arrival is-skeleton" aria-hidden="true">
                        <header class="point-group-head">
                            <span class="point-group-icon"><i class="uil uil-sign-in-alt"></i></span>
                            <span class="route-skeleton-shimmer route-points-skeleton-title"></span>
                            <span class="route-skeleton-shimmer route-points-skeleton-count"></span>
                        </header>
                        <ul class="point-list">
                            <li class="point-item">
                                <span class="point-item-marker"></span>
                                <div class="point-item-body">
                                    <span class="route-skeleton-shimmer route-points-skeleton-name" style="width:68%"></span>
                                    <span class="route-skeleton-shimmer route-points-skeleton-location" style="width:36%"></span>
                                </div>
                                <span class="route-skeleton-shimmer route-points-skeleton-time"></span>
                            </li>
                            <li class="point-item">
                                <span class="point-item-marker"></span>
                                <div class="point-item-body">
                                    <span class="route-skeleton-shimmer route-points-skeleton-name" style="width:74%"></span>
                                    <span class="route-skeleton-shimmer route-points-skeleton-location" style="width:32%"></span>
                                </div>
                                <span class="route-skeleton-shimmer route-points-skeleton-time"></span>
                            </li>
                        </ul>
                    </section>
                </div>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>

    <div class="card">
        <div class="card-header justify-content-between d-flex align-items-center routes-header">
            <div class="card-title">Routes Map</div>
        </div><!-- end card header -->
        <div class="card-body">
            <div id="routes_map" style="height:320px;border-radius:8px;"></div>
            <div id="routes_list"></div>
        </div>
        <!-- end card body -->
    </div>
    <!-- end card -->
</div>
<!-- end col -->
