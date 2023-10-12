 <div class="modal fade myModal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button onclick="show_function('button_area')" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="myModalLabel">Sign up as applicant</h3>
                  </div>
                  <div class="modal-body" >
                    <div class="singUpTutorial">
                        <iframe  src="https://www.youtube.com/embed/uKnU7ks_AR0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <br>
                   <div class="formSection">
                      <form action="" method="POST"  >
                        @csrf
                        <div class="form-group col-md-6">
                            
                            <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                            <input type="hidden" name="user_type" value="{{ encrypt('free_visitor') }}">
                          </div>
                          <div class="form-group col-md-6">
                            <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone">
                          </div>
                          <div class="form-group col-md-6">
                            <input type="text" name="full_name" class="form-control" id="full_name" placeholder="Full Name">
                          </div>
                           
                          <div class="form-group col-md-6">
                            <select class="form-control" name="gender" required>
                                <option value="">Select Gender</option>
                                @forelse($sexs as $sex)
                                <option value="{{$sex->id}}">{!! $sex->name ?? '' !!}</option>
                                @empty
                                @endforelse
                            </select>
                          </div>

                           <div class="form-group col-md-6">
                            <input type="text" name="mother_name" class="form-control" placeholder="Mother Name">
                          </div>
                          <div class="form-group col-md-6">
                            <input type="text" name="father_name" class="form-control" placeholder="Father Name">
                          </div>
                          <div class="form-group col-md-6">
                            <input type="text" name="ssc" class="form-control" placeholder="Secondary School Certificate (SSC) Result">
                          </div>
                          <div class="form-group col-md-6">
                            <input type="text" name="hsc" class="form-control" placeholder="Higher Secondary results (HSC)">
                          </div>
                          
                          
                          <button type="submit" class="btn btn-default welcomeButton form-control">Pay 50 Tk entry fee </button>
                    </form>
                   </div>
                  </div>
                  <div class="modal-footer">
                    <button onclick="show_function('button_area')" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    
                  </div>
                </div>
              </div>
            </div>