<?php

# Each frame or window is designed by extending the wxFrame class and
# adding graphical elements like buttons, menus, text boxes and so on,
# as well as any functions to react to "events" like mouse clicks and
# window closes.

# We'll create a class "MainFrame" that describes our application's window

class MainFrame extends wxFrame
{

# We'll add a function that destroys the frame object when we quit.

  function onQuit()
  {
    $this->Destroy();
  }

# We'll add a function to display an "about" dialog to display some
# information about the application

  function onAbout()
  {

# "wxMessageDialog" is one of the many components or "widgets"
# available in the toolkit. This saves you having to create a
# new frame/window to display your message.

    $dlg = new wxMessageDialog(
      $this,
      "Welcome to wxPHP!!\nBased on wxWidgets 3.0.0\n\n".
      "This is a minimal wxPHP sample!",
      "About box...",
      wxICON_INFORMATION
    );

# Show the dialog box we create above.

    $dlg->ShowModal();
  }

# Add a constructor function. This function is run when we create our
# application window by creating a new object from this class.

  function __construct()
  {

# This calls the constructor function from the wxFrame class that
# we have extended, creating a frame with the title
# "Minimal wxPHP App" in the default position on screen, with
# the initial size of 350 x 260 pixels. Note that this frame is
# not visible by default, it's just created in memory at the
# moment. This means that we can add things to it and fully
# prepare it before we show it to the user, rather than the
# user seeing a blank window that then suddenly fills with
# buttons etc.

    parent::__construct(null, null, "Minimal wxPHP App",
    wxDefaultPosition, new wxSize(350, 260));

# We're going to add menus to our window with various options,
# which means first adding a menu bar into which we put the menus.

    $mb = new wxMenuBar();

# Now we add the menus. First a "File" menu with a "Quit" option

    $mn = new wxMenu();
    $mn->Append(2, "E&xit", "Quit this program");
    $mb->Append($mn, "&File");

# And now a "Help" menu with an "About" option.

    $mn = new wxMenu();
    $mn->AppendCheckItem(4, "&About...", "Show about dialog");
    $mb->Append($mn, "&Help");

# Note the menu and options above all have "&"" symbols in. This
# comes before the letter that should be used for keyboard
# shortcuts. Using a widget toolkit like this means that you don't
# have to write your own code for managing things like keyboard
# shortcuts, saving you time and effort and creating a consistent
# experience for your users.

# Finally add the menu bar to the frame.

    $this->SetMenuBar($mb);

# Lets add a source-code editing box to the frame, which is
# another one of the available widgets in the toolkit and has
# functionality like syntax highlighting, smart indentation etc.

    $scite = new wxStyledTextCtrl($this);

# The final widget we're going to add is a status bar at the
# bottom of the window.

    $sbar = $this->CreateStatusBar(2);
    $sbar->SetStatusText("Welcome to wxPHP...");

# At the start of this class we defined a couple of functions, one
# to show an about box, and one to quit the app. On their own
# they won't do anything, we need to connect them to the menu
# options we created earlier. More specifically, to the
# "wxEVT_COMMAND_MENU_SELECTED" event which is called when the
# user selects something from the menu.

    $this->Connect(2, wxEVT_COMMAND_MENU_SELECTED, array($this,"onQuit"));

    $this->Connect(4, wxEVT_COMMAND_MENU_SELECTED, array($this,"onAbout"));

  }
}

# We've now designed our frame and populated it with widgets and functions
# but at this stage in the code it doesn't yet exist. We need to create
# a new object using our class, which will bring it to life and call
# the constructor function above.

$mf = new mainFrame();

# The frame now exists, it is populated with widgets and the functions
# are all hooked up to the events. However it is hidden, so we need
# to make it visible.

$mf->Show();

# At this point, we need to let wxWidgets take over and run the show.
# Calling wxEntry lets wxWidgets manage the application, wait for and
# react to events and call the functions that we've previously specified.
# As you can see, we need to have specified all of our applications code
# before we get to this point.

wxEntry();

# If we reach this point, it means that our application has quit (either
# the user has closed it or something in our code has closed it), and
# we can either do any tidy-up necessary or just quit.
