@extends('layouts.admin')
@section('content')
    <div class="toast toast-onload align-items-center text-bg-primary border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="toast-body hstack align-items-start gap-6">
            <i class="ti ti-alert-circle fs-6"></i>
            <div>
                <h5 class="text-white fs-3 mb-1">Chào mừng đến PolyFix</h5>
                <h6 class="text-white fs-2 mb-0">No Seat, No Ch!!!</h6>
            </div>
            <button type="button" class="btn-close btn-close-white fs-2 m-0 ms-auto shadow-none" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 d-flex align-items-stretch">
            <div class="d-block w-100">
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mb-1">Earning</h4>
                                <p class="card-subtitle">Last 7 Days</p>
                            </div>
                            <div>
                                <h4 class="card-title mb-1 text-end">12,389</h4>
                                <span
                                    class="badge rounded-pill bg-warning-subtle text-warning border-warning border text-end">-3.8%</span>
                            </div>
                        </div>
                        <div id="total-orders" class="total-orders-chart my-1 mx-n6"></div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-circle text-primary fs-4 me-2"></i>
                                <p class="mb-0">Wrappixel</p>
                            </div>
                            <p class="mb-0">52%</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-circle text-light fs-4 me-2"></i>
                                <p class="mb-0">Wrappixel</p>
                            </div>
                            <p class="mb-0">48%</p>
                        </div>
                    </div>
                </div>
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mb-1">Latest Deal</h4>
                                <p class="card-subtitle">Last 7 Days</p>
                            </div>
                            <div>
                                <span
                                    class="badge rounded-pill bg-success-subtle text-success border-success border text-end">86.5%</span>
                            </div>
                        </div>
                        <div class="my-6 py-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">$98,500</h5>
                                <h6 class="mb-0">$1,22,900</h6>
                            </div>
                            <div class="progress bg-light-subtle w-100 my-2">
                                <div class="progress-bar text-bg-primary" role="progressbar" aria-label="Example 8px high"
                                    style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <p class="mb-0">Coupons used: 18/22</p>
                        </div>
                        <h6 class="mb-7">Recent Purchasers</h6>
                        <ul class="hstack mb-0">
                            <li class="ms-n2">
                                <a href="javascript:void(0)" class="">
                                    <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-2.jpg"
                                        class="rounded-circle border border-2 border-white" width="40" height="40"
                                        alt="spike-img">
                                </a>
                            </li>
                            <li class="ms-n2">
                                <a href="javascript:void(0)" class="">
                                    <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-3.jpg"
                                        class="rounded-circle border border-2 border-white" width="40" height="40"
                                        alt="spike-img">
                                </a>
                            </li>
                            <li class="ms-n2">
                                <a href="javascript:void(0)" class="">
                                    <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-4.jpg"
                                        class="rounded-circle border border-2 border-white" width="40" height="40"
                                        alt="spike-img">
                                </a>
                            </li>
                            <li class="ms-n2">
                                <a href="javascript:void(0)" class="">
                                    <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-5.jpg"
                                        class="rounded-circle border border-2 border-white" width="40" height="40"
                                        alt="spike-img">
                                </a>
                            </li>
                            <li class="ms-n2">
                                <a href="javascript:void(0)"
                                    class="bg-primary-subtle rounded-circle border border-2 border-white d-flex align-items-center justify-content-center round-40">
                                    +8
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body border-bottom position-relative">
                    <h4 class="card-title mb-1">Congratulations Mike</h4>
                    <p class="card-subtitle mb-0">You have done 38% more sales</p>
                    <div class="mt-6">
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex align-items-center mb-9">
                                <div
                                    class="bg-success-subtle p-6 me-3 rounded-circle d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:cart-5-line-duotone"
                                        class="fs-7 text-success"></iconify-icon>
                                </div>
                                <div>
                                    <h6 class="mb-1 fs-4">64 new orders</h6>
                                    <p class="mb-0">Processing</p>
                                </div>
                            </li>
                            <li class="d-flex align-items-center mb-9">
                                <div
                                    class="bg-warning-subtle p-6 me-3 rounded-circle d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:pause-line-duotone"
                                        class="fs-6 text-warning"></iconify-icon>
                                </div>
                                <div>
                                    <h6 class="mb-1 fs-4">4 orders</h6>
                                    <p class="mb-0">On hold</p>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <div
                                    class="bg-indigo-subtle p-6 me-3 rounded-circle d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:bicycling-round-bold-duotone"
                                        class="fs-7 text-indigo"></iconify-icon>
                                </div>
                                <div>
                                    <h6 class="mb-1 fs-4">12 orders</h6>
                                    <p class="mb-0">Delivered</p>
                                </div>
                            </li>
                        </ul>
                        <div class="man-working-on-laptop">
                            <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/backgrounds/man-working-on-laptop.png"
                                alt="spike-img" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="card-body pb-2">
                    <div class="d-flex align-items-baseline justify-content-between">
                        <div>
                            <h4 class="card-title mb-1">Total Orders</h4>
                            <p class="card-subtitle mb-0">Weekly Order Updates</p>
                        </div>
                        <select class="form-select fw-bold w-auto shadow-none">
                            <option value="1">This Week</option>
                            <option value="2">April 2024</option>
                            <option value="3">May 2024</option>
                            <option value="4">June 2024</option>
                        </select>
                    </div>
                    <div id="netsells" class="mx-n6"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 d-flex align-items-stretch">
            <div class="d-block w-100">
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mb-1">Profit</h4>
                                <p class="card-subtitle">Years</p>
                            </div>
                            <div>
                                <h4 class="card-title mb-1 text-end">432</h4>
                                <span
                                    class="badge rounded-pill bg-success-subtle text-success border-success border text-end">+26.5%</span>
                            </div>
                        </div>
                        <div id="products" class="my-8"></div>
                        <p class="mb-0 text-center">$18k Profit more than last years</p>
                    </div>
                </div>
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mb-1">Customers</h4>
                                <p class="card-subtitle">Last 7 Days</p>
                            </div>
                            <div>
                                <h4 class="card-title mb-1 text-end">6,380</h4>
                                <span
                                    class="badge rounded-pill bg-success-subtle text-success border-success border text-end">+26.5%</span>
                            </div>
                        </div>
                        <div id="customers" class="my-5"></div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0">April 07 - April 14</p>
                            <p class="mb-0">6,380</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0">Last Week</p>
                            <p class="mb-0">4,298</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h4 class="card-title">Visit From USA</h4>
                    <div id="usa" class="h-270"></div>
                    <div class="mt-4">
                        <div class="hstack gap-4 mb-4">
                            <h6 class="mb-0 flex-shrink-0 w25">LA</h6>
                            <div class="progress bg-light-subtle mt-1 w-100 h-5">
                                <div class="progress-bar text-bg-info" role="progressbar" style="width: 28%"
                                    aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h6 class="mb-0 flex-shrink-0 w35">28%</h6>
                        </div>
                        <div class="hstack gap-4 mb-4">
                            <h6 class="mb-0 flex-shrink-0 w25">NY</h6>
                            <div class="progress bg-light-subtle mt-1 w-100 h-5">
                                <div class="progress-bar text-bg-primary" role="progressbar" style="width: 21%"
                                    aria-valuenow="21" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h6 class="mb-0 flex-shrink-0 w35">21%</h6>
                        </div>
                        <div class="hstack gap-4 mb-4">
                            <h6 class="mb-0 flex-shrink-0 w25">KA</h6>
                            <div class="progress bg-light-subtle mt-1 w-100 h-5">
                                <div class="progress-bar text-bg-danger" role="progressbar" style="width: 18%"
                                    aria-valuenow="18" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h6 class="mb-0 flex-shrink-0 w35">18%</h6>
                        </div>
                        <div class="hstack gap-4">
                            <h6 class="mb-0 flex-shrink-0 w25">AZ</h6>
                            <div class="progress bg-light-subtle mt-1 w-100 h-5">
                                <div class="progress-bar text-bg-indigo" role="progressbar" style="width: 12%"
                                    aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h6 class="mb-0 flex-shrink-0 w35">12%</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="table-responsive overflow-x-auto products-tabel">
                        <table class="table text-nowrap customize-table mb-0 align-middle">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th>Products</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center product text-truncate">
                                            <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/products/product-7.png"
                                                class="img-fluid flex-shrink-0" width="60" height="60">
                                            <div class="ms-3 product-title">
                                                <h6 class="fs-4 mb-0 text-truncate-2">PlayStation 5
                                                    DualSense Wireless Controller</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="mb-0 fs-4">$120 <span class="text-muted">/499</span>
                                        </h5>
                                        <p class="text-muted mb-2">Cancelled</p>
                                        <div class="progress bg-light-subtle w-100 h-4">
                                            <div class="progress-bar text-bg-danger" role="progressbar"
                                                aria-label="Example 4px high" style="width: 100%;" aria-valuenow="100"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-pill bg-danger-subtle text-danger border-danger border">Cancelled</span>
                                    </td>
                                    <td>
                                        <div class="dropdown dropstart">
                                            <a href="javascript:void(0)" class="text-muted " id="dropdownMenuButton"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical fs-5"></i>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-3"
                                                        href="javascript:void(0)">
                                                        <i class="fs-4 ti ti-plus"></i>Add
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-3"
                                                        href="javascript:void(0)">
                                                        <i class="fs-4 ti ti-edit"></i>Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-3"
                                                        href="javascript:void(0)">
                                                        <i class="fs-4 ti ti-trash"></i>Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center product text-truncate">
                                            <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/products/product-9.png"
                                                class="img-fluid flex-shrink-0" width="60" height="60">
                                            <div class="ms-3 product-title">
                                                <h6 class="fs-4 mb-0 text-truncate-2">Sony X85J 75
                                                    Inch Sony 4K Ultra HD LED Smart G...
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="mb-0 fs-4">$120 <span class="text-muted">/499</span>
                                        </h5>
                                        <p class="text-muted mb-2">Full paid</p>
                                        <div class="progress bg-light-subtle w-100 h-4">
                                            <div class="progress-bar text-bg-success" role="progressbar"
                                                aria-label="Example 4px high" style="width: 100%;" aria-valuenow="100"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-pill bg-success-subtle text-success border-success border">Confirmed</span>
                                    </td>
                                    <td <div class="dropdown dropstart">
                                        <a href="javascript:void(0)" class="text-muted " id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-plus"></i>Add
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-edit"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-trash"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                    </div>
                    </td>
                    </tr>
                    <tr>
                        <td class="ps-0">
                            <div class="d-flex align-items-center product text-truncate">
                                <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/products/product-6.png"
                                    class="img-fluid flex-shrink-0" width="60" height="60">
                                <div class="ms-3 product-title">
                                    <h6 class="fs-4 mb-0 text-truncate-2">Apple MacBook Pro 13
                                        inch-M1-8/256GB-space</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <h5 class="mb-0 fs-4">$120 <span class="text-muted">/499</span>
                            </h5>
                            <p class="text-muted mb-2">Full paid</p>
                            <div class="progress bg-light-subtle w-100 h-4">
                                <div class="progress-bar text-bg-success" role="progressbar"
                                    aria-label="Example 4px high" style="width: 100%;" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </td>
                        <td>
                            <span
                                class="badge rounded-pill bg-success-subtle text-success border-success border">Confirmed</span>
                        </td>
                        <td>
                            <div class="dropdown dropstart">
                                <a href="javascript:void(0)" class="text-muted " id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical fs-5"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="javascript:void(0)">
                                            <i class="fs-4 ti ti-plus"></i>Add
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="javascript:void(0)">
                                            <i class="fs-4 ti ti-edit"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="javascript:void(0)">
                                            <i class="fs-4 ti ti-trash"></i>Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-0">
                            <div class="d-flex align-items-center product text-truncate">
                                <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/products/product-8.png"
                                    class="img-fluid flex-shrink-0" width="60" height="60">
                                <div class="ms-3 product-title">
                                    <h6 class="fs-4 mb-0 text-truncate-2">Amazon Basics Mesh,
                                        Mid-Back, Swivel Office De...
                                    </h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <h5 class="mb-0 fs-4">$120 <span class="text-muted">/499</span>
                            </h5>
                            <p class="text-muted mb-2">Partially paid</p>
                            <div class="progress bg-light-subtle w-100 h-4">
                                <div class="progress-bar text-bg-warning" role="progressbar"
                                    aria-label="Example 4px high" style="width: 40%;" aria-valuenow="40"
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </td>
                        <td>
                            <span
                                class="badge rounded-pill bg-indigo-subtle text-indigo border-indigo border">Confirmed</span>
                        </td>
                        <td>
                            <div class="dropdown dropstart">
                                <a href="javascript:void(0)" class="text-muted " id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical fs-5"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="javascript:void(0)">
                                            <i class="fs-4 ti ti-plus"></i>Add
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="javascript:void(0)">
                                            <i class="fs-4 ti ti-edit"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="javascript:void(0)">
                                            <i class="fs-4 ti ti-trash"></i>Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-0 border-bottom-0">
                            <div class="d-flex align-items-center product text-truncate">
                                <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/products/product-5.png"
                                    class="img-fluid flex-shrink-0" width="60" height="60">
                                <div class="ms-3 product-title">
                                    <h6 class="fs-4 mb-0 text-truncate-2">iPhone 13 pro max-Pacific
                                        Blue-128GB storage</h6>
                                </div>
                            </div>
                        </td>
                        <td class="border-bottom-0">
                            <h5 class="mb-0 fs-4">$180 <span class="text-muted">/499</span>
                            </h5>
                            <p class="text-muted mb-2">Partially paid</p>
                            <div class="progress bg-light-subtle w-100 h-4">
                                <div class="progress-bar text-bg-warning" role="progressbar"
                                    aria-label="Example 4px high" style="width: 40%;" aria-valuenow="40"
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </td>
                        <td class="border-bottom-0">
                            <span
                                class="badge rounded-pill bg-indigo-subtle text-indigo border-indigo border">Confirmed</span>
                        </td>
                        <td class="border-bottom-0">
                            <div class="dropdown dropstart">
                                <a href="javascript:void(0)" class="text-muted " id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical fs-5"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="javascript:void(0)">
                                            <i class="fs-4 ti ti-plus"></i>Add
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="javascript:void(0)">
                                            <i class="fs-4 ti ti-edit"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3"
                                            href="javascript:void(0)">
                                            <i class="fs-4 ti ti-trash"></i>Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card mb-0">
            <div class="card-body">
                <div class="d-md-flex justify-content-between mb-9">
                    <div class="mb-9 mb-md-0">
                        <h4 class="card-title">Latest Reviews</h4>
                        <p class="card-subtitle mb-0">Review received across all channels</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <form class="position-relative me-3 w-100">
                            <input type="text" class="form-control search-chat py-2 ps-5" id="text-srh"
                                placeholder="Search">
                            <i
                                class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                        </form>
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="btn border-dark-subtle shadow-none px-3"
                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical fs-5"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                        <i class="fs-4 ti ti-plus"></i>Add
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                        <i class="fs-4 ti ti-edit"></i>Edit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                        <i class="fs-4 ti ti-trash"></i>Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="table-responsive overflow-x-auto latest-reviews-table">
                    <table class="table align-middle text-nowrap">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="ps-0">
                                    #
                                </th>
                                <th>Products</th>
                                <th>Customer</th>
                                <th>Reviews</th>
                                <th>Status</th>
                                <th>Time</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-0">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckDefault1">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center product text-truncate">
                                        <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/products/product-5.png"
                                            class="img-fluid flex-shrink-0" width="60" height="60">
                                        <div class="ms-3 product-title">
                                            <h6 class="fs-4 mb-0 text-truncate-2">iPhone 13 pro
                                                max-Pacific Blue-128GB storage</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-truncate">
                                        <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-2.jpg"
                                            alt="spike-img" class="img-fluid rounded-circle flex-shrink-0" width="40"
                                            height="40">
                                        <div class="ms-3">
                                            <h4 class="card-title mb-1 fs-4">Arlene McCoy</h4>
                                            <p class="card-subtitle">macoy@arlene.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-reviews">
                                        <ul class="list-unstyled d-flex align-items-center mb-0">
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold-duotone"
                                                        class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="" href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-line-duotone"
                                                        class="text-warning"></iconify-icon></a>
                                            </li>
                                        </ul>
                                        <p class="text-dark mb-0 fw-normal text-truncate-2">
                                            This theme is great. Clean and easy to
                                            understand. Perfect for those who don't have
                                            <br>
                                            time to... <a href="javascript:void(0)">See more</a>
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge rounded-pill bg-success-subtle text-success border-success border">Confirmed</span>
                                </td>
                                <td>
                                    <p class="mb-0">Nov 8</p>
                                </td>
                                <td>
                                    <div class="dropdown dropstart">
                                        <a href="javascript:void(0)" class="text-muted " id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-plus"></i>Add
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-edit"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-trash"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-0">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckDefault2">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center product text-truncate">
                                        <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/products/product-6.png"
                                            class="img-fluid flex-shrink-0" width="60" height="60">
                                        <div class="ms-3 product-title">
                                            <h6 class="fs-4 mb-0 text-truncate-2">Apple MacBook Pro 13
                                                inch-M1-8/256GB-space</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-truncate">
                                        <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-3.jpg"
                                            alt="spike-img" class="img-fluid rounded-circle flex-shrink-0" width="40"
                                            height="40">
                                        <div class="ms-3">
                                            <h4 class="card-title mb-1 fs-4">Jerome Bell</h4>
                                            <p class="card-subtitle">belljerome@yahoo.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-reviews">
                                        <ul class="list-unstyled d-flex align-items-center mb-0">
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="" href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-line-duotone"
                                                        class="text-warning"></iconify-icon></a>
                                            </li>
                                        </ul>
                                        <p class="text-dark mb-0 fw-normal text-truncate-2">
                                            It's a Mac, after all. Once you've gone Mac,there's no going
                                            back. My first Mac
                                            lastedover nine years.
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge rounded-pill bg-warning-subtle text-warning border-warning border">Pending</span>
                                </td>
                                <td>
                                    <p class="mb-0">Nov 8</p>
                                </td>
                                <td>
                                    <div class="dropdown dropstart">
                                        <a href="javascript:void(0)" class="text-muted " id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-plus"></i>Add
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-edit"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-trash"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-0">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckDefault3">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center product text-truncate">
                                        <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/products/product-7.png"
                                            class="img-fluid flex-shrink-0" width="60" height="60">
                                        <div class="ms-3 product-title">
                                            <h6 class="fs-4 mb-0 text-truncate-2">PlayStation 5
                                                DualSense Wireless Controller</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-truncate">
                                        <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-4.jpg"
                                            alt="spike-img" class="img-fluid rounded-circle flex-shrink-0" width="40"
                                            height="40">
                                        <div class="ms-3">
                                            <h4 class="card-title mb-1 fs-4">Jacob Jones</h4>
                                            <p class="card-subtitle">jones009@hotmail.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-reviews">
                                        <ul class="list-unstyled d-flex align-items-center mb-0">
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold-duotone"
                                                        class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="" href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-line-duotone"
                                                        class="text-warning"></iconify-icon></a>
                                            </li>
                                        </ul>
                                        <p class="text-dark mb-0 fw-normal text-truncate-2">
                                            The best experience we could hope for.Customer service team
                                            is amazing and thequality
                                            of their products... <a href="javascript:void(0)">See
                                                more</a>
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge rounded-pill bg-warning-subtle text-warning border-warning border">Pending</span>
                                </td>
                                <td>
                                    <p class="mb-0">Nov 8</p>
                                </td>
                                <td>
                                    <div class="dropdown dropstart">
                                        <a href="javascript:void(0)" class="text-muted " id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-plus"></i>Add
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-edit"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-trash"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-0">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckDefault4">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center product text-truncate">
                                        <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/products/product-8.png"
                                            class="img-fluid flex-shrink-0" width="60" height="60">
                                        <div class="ms-3 product-title">
                                            <h6 class="fs-4 mb-0 text-truncate-2">Amazon Basics Mesh,
                                                Mid-Back, Swivel Office De...
                                            </h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-truncate">
                                        <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-5.jpg"
                                            alt="spike-img" class="img-fluid rounded-circle flex-shrink-0" width="40"
                                            height="40">
                                        <div class="ms-3">
                                            <h4 class="card-title mb-1 fs-4">Annette Black</h4>
                                            <p class="card-subtitle">blackanne@yahoo.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-reviews">
                                        <ul class="list-unstyled d-flex align-items-center mb-0">
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold-duotone"
                                                        class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="" href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-line-duotone"
                                                        class="text-warning"></iconify-icon></a>
                                            </li>
                                        </ul>
                                        <p class="text-dark mb-0 fw-normal text-truncate-2">
                                            The controller is quite comfy for me. Despiteits increased
                                            size, the controller still
                                            fits well
                                            <br>in my hands.
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge rounded-pill bg-success-subtle text-success border-success border">Confirmed</span>
                                </td>
                                <td>
                                    <p class="mb-0">Nov 8</p>
                                </td>
                                <td>
                                    <div class="dropdown dropstart">
                                        <a href="javascript:void(0)" class="text-muted " id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-plus"></i>Add
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-edit"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-trash"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-0">
                                    <div class="form-check mb-0 flex-shrink-0">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckDefault5">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center product text-truncate">
                                        <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/products/product-9.png"
                                            class="img-fluid flex-shrink-0" width="60" height="60">
                                        <div class="ms-3 product-title">
                                            <h6 class="fs-4 mb-0 text-truncate-2">Sony X85J 75 Inch
                                                Sony 4K Ultra HD LED Smart G...
                                            </h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-truncate">
                                        <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-6.jpg"
                                            alt="spike-img" class="img-fluid rounded-circle flex-shrink-0" width="40"
                                            height="40">
                                        <div class="ms-3">
                                            <h4 class="card-title mb-1 fs-4">Albert Flores</h4>
                                            <p class="card-subtitle">albertflo9@gmail.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-reviews">
                                        <ul class="list-unstyled d-flex align-items-center mb-0">
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold" class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="me-1 " href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-bold-duotone"
                                                        class="text-warning"></iconify-icon></a>
                                            </li>
                                            <li>
                                                <a class="" href="javascript:void(0)"><iconify-icon
                                                        icon="solar:star-line-duotone"
                                                        class="text-warning"></iconify-icon></a>
                                            </li>
                                        </ul>
                                        <p class="text-dark mb-0 fw-normal text-truncate-2">
                                            This theme is great. Perfect for those whodon't have time to
                                            start everything from
                                            <br>scratch.
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge rounded-pill bg-warning-subtle text-warning border-warning border">Pending</span>
                                </td>
                                <td>
                                    <p class="mb-0">Nov 8</p>
                                </td>
                                <td>
                                    <div class="dropdown dropstart">
                                        <a href="javascript:void(0)" class="text-muted " id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-plus"></i>Add
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-edit"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                    href="javascript:void(0)">
                                                    <i class="fs-4 ti ti-trash"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-10">
                    <p class="mb-0 fw-normal">1-6 of 32</p>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination mb-0 align-items-center">
                            <li class="page-item">
                                <a class="page-link border-0 d-flex align-items-center text-muted fw-normal"
                                    href="javascript:void(0)"><iconify-icon icon="solar:alt-arrow-left-line-duotone"
                                        class="fs-5"></iconify-icon>Previous</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link border-0 d-flex align-items-center fw-normal"
                                    href="javascript:void(0)">Next<iconify-icon icon="solar:alt-arrow-right-line-duotone"
                                        class="fs-5"></iconify-icon></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
