<div class="modal fade" id="SignUpasvisitorforfree" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button onclick="show_function('button_area')" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="myModalLabel">Sign Up as visitor for free</h3>
                  </div>
                  <div class="modal-body" style="max-width: 500px;margin:0px auto;">
                    <div class="singUpTutorial">
                        <iframe  src="https://www.youtube.com/embed/uKnU7ks_AR0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <form action="" method="POST" >
                        @csrf
                        <div class="form-group">
                            
                            <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                            <input type="hidden" name="user_type" value="{{ encrypt('free_visitor') }}">
                          </div>
                          <div class="form-group">
                            <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone">
                          </div>
                          <div class="form-group">
                            <select class="form-control" name="board" required>
                                <option value="">Select Board</option>
                                @forelse($boards as $board)
                                <option value="{{$board->id}}">{!! $board->name ?? '' !!}</option>
                                @empty
                                @endforelse
                            </select>
                          </div>
                           <div class="form-group">
                            <input type="text" name="university" class="form-control" placeholder="University/ institution Name">
                          </div>
                          <div class="form-group">
                            <input type="text" name="study_field" class="form-control" placeholder="Field of study">
                          </div>
                          
                          
                          <button type="submit" class="btn btn-default welcomeButton form-control">Submit</button>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button onclick="show_function('button_area')" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                  </div>
                </div>
              </div>
            </div>