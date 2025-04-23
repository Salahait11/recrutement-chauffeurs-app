                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ App\Models\LeaveRequest::getStatusBadgeClass($request->status) }}">
                                        {{ App\Models\LeaveRequest::getStatusList()[$request->status] ?? $request->status }}
                                    </span>
                                </td> 