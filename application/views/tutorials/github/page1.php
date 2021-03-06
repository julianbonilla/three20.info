How to build a GitHub iPhone app with three20
=============================================

<div class="source">
  <?= $navLinks ?>
</div>

Please note: this tutorial is a work in progress
------------------------------------------------

We're going to build a GitHub iPhone app using the public [GitHub api](http://develop.github.com/).
The goal throughout these tutorials is to introduce you to the various aspects of three20 while
building something with a clear final result and purpose.

Let's get started!

To begin, let's start a new project. We're going to build this app using Core Data (don't worry if
you haven't used it yet, it's surprisingly easy to pick up). To speed this step up, I'm going to
use [the three20 project templates](/setup/templates).

<div class="image" markdown=1>![Create a new project](/gfx/tutorial/github/newproject.png 409x318)</div>

Once you've created the project, make sure you can build and run it. The default three20 template
app should load three20.info in a web browser.

Let's start looking at the code.

Three20 and URL-Based Navigation
================================

Open AppDelegate.m and look at the following code in `applicationDidFinishLaunching`.

<div class="sectiontags" markdown="1">
* AppDelegate.m
</div>
<div class="clearfix"></div>

    TTNavigator* navigator = [TTNavigator navigator];
    navigator.persistenceMode = TTNavigatorPersistenceModeAll;

While we're developing we don't want the navigator to persist the navigation
history (unless we're testing persistence), so for the time being let's change the persistence mode
to none.

<div class="sectiontags" markdown="1">
* AppDelegate.m
* modifications
</div>
<div class="clearfix"></div>

    navigator.persistenceMode = TTNavigatorPersistenceModeNone;

Now let's look at the URL mappings.

<div class="sectiontags" markdown="1">
* AppDelegate.m
</div>
<div class="clearfix"></div>

    TTURLMap* map = navigator.URLMap;
    [map from:@"*" toViewController:[TTWebController class]];

This is where we start adding the basic navigation mappings. The `@"*"` mapping will catch any url
and open it with the standard three20 web controller, which displays a web view with standard
toolbar buttons. We could easily change this to map to a different controller if we chose, but for
this tutorial we'll leave it alone.

After we've set up the mapping for the app, we kick the navigator into gear. We first attempt to
restore the view controller hierarchy from when we previously ran the app, and if that fails, we
load three20.info.

<div class="sectiontags" markdown="1">
* AppDelegate.m
</div>
<div class="clearfix"></div>

    if (![navigator restoreViewControllers]) {
      [navigator openURL:@"http://three20.info" animated:NO];
    }

<div class="image" markdown=1>![three20.info](/gfx/tutorial/github/three20info.png 320x480)</div>

Adding Our First Three20 Controller
===================================

Let's start off with something personable; a user view.

This part of the tutorial is going to cover creating a three20 table view controller, building a
url request-based model, and populating the table with content retrieved from the web.

Let's start by creating a new view controller. You can name it whatever you like, but I'll refer
to it as `UserViewController` from here on out. I create my view controllers from the
Objective-C class template to avoid having to remove the stock code placed in the UIViewController
subclass template, so bear that in mind as we plow through the code.

Once you've created your controller, open the .h file and replace UIViewController with
TTTableViewController. We're going to create our user view controller using a github username, so
we'll also add a username property.

<div class="sectiontags" markdown="1">
* UserViewController.h
* New File
</div>
<div class="clearfix"></div>

    @interface UserViewController : TTTableViewController {
      NSString* _username;
    }

    @property (nonatomic, copy) NSString* username;

    @end

Then, in the .m file, we'll add the following initializers.

<div class="sectiontags" markdown="1">
* UserViewController.m
* New File
</div>
<div class="clearfix"></div>

    @implementation UserViewController

    @synthesize username = _username;

    - (id)initWithUsername:(NSString*)username {
      // Note the [self init] here instead of [super init].
      if (self = [self init]) {
        self.username = username;
        self.title = username;
      }

      return self;
    }

    - (id)init {
      if (self = [super init]) {
        self.tableViewStyle = UITableViewStyleGrouped;
        self.variableHeightRows = YES;
        self.title = @"User Info";
      }

      return self;
    }

    - (void)dealloc {
      TT_RELEASE_SAFELY(_username);
      [super dealloc];
    }

    @end

Now let's start actually seeing some progress.

Start by heading back to AppDelegate.m and adding `UserViewController.h` to the list of includes.
Then add the following mapping to `applicationDidFinishLaunching`.

<div class="sectiontags" markdown="1">
* AppDelegate.m
* new code and modifications
</div>
<div class="clearfix"></div>

    #import "UserViewController.h"

    - (void)applicationDidFinishLaunching:(UIApplication *)application {

      ...
    
      [map from:@"*" toViewController:[TTWebController class]];

      [map from:@"http://github.com/(initWithUsername:)"
           toViewController:[UserViewController class]];

Our goal here is to map URLs like `http://github.com/jverkoey` to our UserViewController object. When
we open any url with this format, a new UserViewController object will be created and
`initWithUsername:` will be called.

So let's see it in action. We'll modify the default URL we open the app with:

<div class="sectiontags" markdown="1">
* AppDelegate.m
* modification
</div>
<div class="clearfix"></div>

    if (![navigator restoreViewControllers]) {
      [navigator openURLAction:[TTURLAction
        actionWithURLPath:@"http://github.com/your_username"]];
    }

Try running the app now and this is what you should see.

<div class="image" markdown=1>![Loading...](/gfx/tutorial/github/userloadingview.png 320x480)</div>

### Troubleshooting

**After changing the URL in `navigator openURLAction` and running the app, it still loads the
web view. What gives?**

> This is a result of `[navigator restoreViewControllers]`. The app stores its navigation history
> when the app closes, so if you closed the app with the web view open and the persistence
> mode was still set to "All", your navigation history currently contains the URL
> `http://three20.info`
>
> To fix this, simply delete the app and rebuild. This will clear the navigation history.

[Continue this tutorial on page 2...](/tutorials/github/page/2)
