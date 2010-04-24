<?= $title ?>
=============

Three20 is a growing library of code. Here are a couple ways you can contribute to the project.

Via GitHub
----------

If you have a fork of three20 on GitHub, then you can make a pull request from the GitHub interface.

Here's [GitHub's pull request guide](http://github.com/guides/pull-requests).

Contribute to three20.info
--------------------------

We've taken a rather odd approach to managing the three20.info website; we've open-sourced it on
GitHub.

This means that you can fork the site, contribute your own articles, fixes, and
updates, and we'll merge it back into the live site. It's basically a wiki, but without the
complicated wiki backend.

All of the articles on the site are written with Markdown.

[Fork the site](http://github.com/jverkoey/three20.info) on github.

Making Pull Requests on GitHub
------------------------------

If you want to improve the likelihood of your bug or feature being seen in mainline Three20,
then follow this guideline for making pull requests.

For bugs:

* Include a reproduction scenario (also called a repro case). This should be either
  **a detailed enough verbal description to recreate the bug in question**, or, even better, a
  project that clearly shows the bug in question.
* If you have a solution, commit it to your personal Three20 repository on GitHub and then make
  a pull request. It's way easier than copy-pasting code and it ensures that your name will be
  attached to the fix.

For features:

* Provide detailed documentation for each new feature you provide, as well as the rationale for
  why you think it is necessary. Three20 has a lot of room to grow, but we need to ensure that it
  grows at a sustainable rate.
* If you don't intend to implement the feature, then document the steps required to
  implement the feature to the best of your knowledge. The more information you provide, the more
  likely your feature will be realized.

[Permalink]."permalink"(#styleguidelines)
Source code style guidelines {#styleguidelines}
============================

Three20 uses a consistent style guideline throughout the entire code base. The
various guidelines are presented below, separated into three sections:
[general guidelines](#general), [guidelines for .h files](#headerguidelines), and
[guidelines for .m files](#sourceguidelines).
Any code that does not follow these guidelines will not be merged into the mainline of Three20.

General Guidelines {#general}
------------------
[Permalink]."permalink"(#general)

Presented below are a site of guidelines that apply to every source file in Three20. This includes
header (.h) and source (.m) files.

[Permalink]."permalink"(#preamble)
### The Preamble {#preamble}

    //
    // Copyright 2009-2010 Facebook
    //
    // Licensed under the Apache License, Version 2.0 (the "License");
    // you may not use this file except in compliance with the License.
    // You may obtain a copy of the License at
    //
    //    http://www.apache.org/licenses/LICENSE-2.0
    //
    // Unless required by applicable law or agreed to in writing, software
    // distributed under the License is distributed on an "AS IS" BASIS,
    // WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    // See the License for the specific language governing permissions and
    // limitations under the License.
    //

This is required at the top of each source file.

!> !* Rationale
!>
!> In the event that a given source file is copied into another project,
!> this ensures to a reasonable extent that the license is preserved and that original ownership
!> is noted.
!> 
!> Open for debate: The size of the preamble could potentially be minimized to a single line.

[Permalink]."permalink"(#imports)
### Imports {#imports}

    // UI
    #import "Three20/TTURLNavigatorPattern.h"
    #import "Three20/TTURLGeneratorPattern.h"

    // Core
    #import "Three20/TTGlobalCore.h"
    #import "Three20/TTCorePreprocessorMacros.h"

Group all imports into their logical Three20 section and prefix a given section with a comment
stating which section you intend it to be. Remember, Three20 is split into Core,
Network, Style, and UI.

!> !* Rationale
!>
!> By grouping imports into logical groups, it's easier to read and quickly understand
!> what dependencies this source has with respect to the given sections of Three20.


[Permalink]."permalink"(#importorder)
<div id="importorder"></div>
The order of imports should always be the following:

* UI
* Style
* Network
* Core
* Apple Frameworks

!> !* Rationale
!>
!> By placing the Apple Frameworks at the bottom, we decrease the chances of import order
!> mattering. For example:
!> 
!>     #import <Foundation/Foundation.h>
!>     
!>     // UI
!>     #import "Three20/TTURLNavigatorPattern.h"
!> 
!> Let's say that TTURLNavigatorPattern.h depended on Foundation.h for NSObject, but it didn't
!> actually include Foundation.h within itself. If this is the only place that
!> TTURLNavigatorPattern is imported, the project will build successfully right now. But if we
!> then try to import ***only*** TTURLNavigatorPattern.h somewhere else, without placing
!> Foundation.h above it, we'll get a build error.

Within a group, TTGlobal* headers should come first (e.g. `TTGlobalCore.h`).

!> !* Rationale
!>
!> This rationale is contrary to the above goal of reducing import order errors. The goal of
!> placing TTGlobal* imports at the top of a logical group is to make it easy to absorb the set of
!> imports used in any given group.
!> 
!> This guideline is subject to debate.

[Permalink]."permalink"(#minimalimports)
<div id="minimalimports"></div>
Make an effort to include the minimal number of header files in each source file.

!> !* Rationale
!>
!> This will reduce incremental build times and make it clear as to which objects the
!> given class legitimately depends on.

### General style guidelines

[Permalink]."permalink"(#maxlinelength)
<div id="maxlinelength"></div>
The maximum line length is 100 characters.

!> !* Rationale
!>
!> Due to the verboseness of Objective-C, the difference between 100 characters and 80 is quite
!> noticeable. We're also not using strict 80 character terminals for development anymore. 100 was
!> chosen due to the ability of laying out two source files side-by-side on the screen, while still
!> seeing all code.


[Permalink]."permalink"(#spaces)
<div id="spaces"></div>
Three20 uses spaces, not tabs.

!> !* Rationale
!>
!> It is impossible to guarantee that everyone is using the same IDE with the same tab spacing.
!> Also, Three20 frequently uses alignment to make code more readable. As such, we use spaces
!> instead of tabs to guarantee that the code is always presented consistently.


[Permalink]."permalink"(#indentation)
<div id="indentation"></div>
Three20 uses two spaces for indentation.

!> !* Rationale
!>
!> This point is purely religious. Two spaces was chosen due to historical use, as well as the
!> minimal amount of space it uses (compared to four spaces).




Guidelines for .h files {#headerguidelines}
-----------------------

[Permalink]."permalink"(#headerimports)
### Header Imports {#headerimports}

    #import <Foundation/Foundation.h>
    #import <UIKit/UIKit.h>

Objects that don't inherit from any other TT* object should import the required
frameworks for the object in question. For example, `TTURLRequestQueue` needs Foundation.h in
order to inherit from NSObject and it needs UIKit.h for a CGFloat instance variable.

!> !* Rationale
!>
!> Three20 does, in fact, import the above frameworks in the precompiled header. However, the goal
!> of Three20 is to be independent of precompiled headers if a developer ever needed to build
!> a subset of Three20 without a precompiled header. For this reason, we must import frameworks
!> as necessary.
!> 
!> In order to verify that you are doing this correctly, use the "Debug Dev" build, which is a
!> special internal build that does not use precompiled headers. A nice side effect of this is
!> that if it builds in Debug Dev, it's guaranteed to build in Debug and Release (the inverse
!> is not strictly true).
!> 
!> Side note: Debug and Release Three20 builds both use a precompiled header.

    // Network
    #import "Three20/TTURLResponse.h"

For objects that inherit from other TT* objects, you don't need to import the frameworks.

!> !* Rationale
!>
!> The inherited class file is sufficient because it will generally import the required frameworks.
!> 
!> !* Exception
!> Sometimes the inherited class file won't import all of the necessary frameworks. If this happens,
!> import the frameworks, still keeping in mind the [import order](#imports).

[Permalink]."permalink"(#advancedecls)
### Required classes/prototypes {#advancedecls}

    @class TTURLRequest;

Unless you're inheriting from a class or implementing a protocol, it is adequate
to provide an advance declaration of the class instead of importing the header file for
the class.

!> !* Rationale
!>
!> This will reduce header file dependencies and therefor improve incremental build times.

[Permalink]."permalink"(#classdefs)
### Class definition {#classdefs}

    @interface TTModelViewController : TTViewController <TTModelDelegate> {

Three20 classes are always prefixed with TT. This is an example of an object that inherits from
another Three20 class and implements a Three20 protocol.

!> !* Rationale
!>
!> Class prefixing is a common practice for avoiding namespace conflicts.

    @interface CustomModelViewController : TTViewController <
      TTModelDelegate,
      TTURLRequestDelegate
    > {

If the object implements multiple protocols, split the protocol list into
separate lines with the above format, one protocol on each line.

!> !* Rationale
!>
!> This improves the readability of diffs if/when you change the protocols that this class imports.
!> This also makes it easier to quickly read the set of implemented protocols.

[Permalink]."permalink"(#classivars)
### Class ivars {#classivars}

    @private
      id              _userInfo;
      NSMutableArray* _urls;

      id<TTActionSheetControllerDelegate> _delegate;

Instance variables (ivars) are explicitly declared private.

!> !* Rationale
!>
!> Ivars should never be accessed directly from an outside class; this is what
!> [properties](#properties) are for.

[Permalink]."permalink"(#ivarprefix)
<div id="ivarprefix"></div>
Ivar names are prefixed with an underscore (`_`).

!> !* Rationale
!>
!> This makes it easy to differentiate between a property accessor (`self.userInfo`)
!> and accessing a private ivar (`_userInfo`).
!> 
!> `_` is a prefix instead of a suffix because it immediately narrows down autocompletion
!> to private ivars when you type `_`. This is counter to Apple's recommendation of using `_` as a
!> suffix for private ivars.

[Permalink]."permalink"(#ivarcasing)
<div id="ivarcasing"></div>
Ivar names always begin with a lower-case letter and are camelCased.

!> !* Rationale
!>
!> This then transfers to property names, making it easy to begin typing a property name with
!> only one keypress (no shift key involved).
!> 
!> With respect to acronyms, you should still use use a lowercase first letter. For example:
!> `_urlPath`, instead of `_URLPath`.

[Permalink]."permalink"(#ivargrouping)
<div id="ivargrouping"></div>
Ivars must be grouped in some logical grouping.

!> !* Rationale
!>
!> This makes it easier to understand the contents of the class. Space the ivar names such
!> that they align vertically in each logical group.

[Permalink]."permalink"(#ivardelegates)
<div id="ivardelegates"></div>
Delegates and other protocol objects must be placed at the bottom of the ivar definitions.

!> !* Rationale
!>
!> Delegates are generally much longer declarations and would otherwise throw off the alignment of
!> the other ivars.

[Permalink]."permalink"(#properties)
### Properties {#properties}

    /**
     * The maximum size of a download that is allowed.
     *
     * If a response reports a content length greater than the max, the download
     * will be cancelled. This is helpful for preventing excessive memory usage.
     * Setting this to zero will allow all downloads regardless of size.
     *
     * @default 150000 bytes
     */
    @property (nonatomic) NSUInteger maxContentLength;

Due to our use of Doxygen to generate the API documentation, we use javadoc-style documentation
syntax throughout the source. The first sentence of a comment will be the "summary" line shown in
the API docs, and any subsequent lines will be in the "more details" section.

[Permalink]."permalink"(#ivarnonatomic)
<div id="ivarnonatomic"></div>
Unless you're certain of what you're doing, use `nonatomic` when declaring properties.

!> !* Rationale
!>
!> The alternative is atomic, which has potential performance implications that may be undesirable.

[Permalink]."permalink"(#ivaraccess)
<div id="ivaraccess"></div>
Be specific about the access level of the property. For example, properties that shouldn't
be modifiable outside of the class should be `readonly`.

!> !* Rationale
!>
!> Don't depend on people's memory to recall what the default property access levels are.
!> Unless you're 100% confident that 100% of people reading the code will remember the implicit
!> access level, make it explicit.

[Permalink]."permalink"(#ivarmemory)
<div id="ivarmemory"></div>
Understand the difference between the `retain`, `copy`, and `assign` keywords. A general
rule of thumb is to use `copy` for NSStrings, `retain` for objects whose lifetime you want
to guarantee, and `assign` for delegates.

!> !* Rationale
!>
!> This doesn't need much explaining. You either do this right, or you don't and suffer the
!> memory hazards as a result.




Guidelines for .m files {#sourceguidelines}
-----------------------

    ///////////////////////////////////////////////////////////////////////////////////////////////////

[Permalink]."permalink"(#commentlines)
<div id="commentlines"></div>

Before every method declaration there should be a comment line that is exactly 99 characters wide.

Before every class declaration there should be three of the above comment lines.

!> !* Rationale
!>
!> These comment lines help bring focus to the start of a method better than simple whitespace.
!> They also act as a bounds checker when writing long lines of code.

### Initialization and deallocation

[Permalink]."permalink"(#initdealloc)
<div id="initdealloc"></div>

init and dealloc methods should always be the first methods in the class implementation.

!> !* Rationale
!>
!> init and dealloc are two most common methods used in classes, and therefor should be the
!> easiest to find.

    - (id)init {
      if (self = [self initWithTarget:nil]) {
      }

      return self;
    }

[Permalink]."permalink"(#designatedinitializer)
<div id="designatedinitializer"></div>

Every class should have a designated initializer which calls the `super` class's designated
initializer. All other init methods for the class should call the local class's designated
initializer using `self`.

!> !* Rationale
!>
!> See the Apple documentation on [designated initializers](http://developer.apple.com/mac/library/documentation/cocoa/conceptual/ObjectiveC/Articles/ocAllocInit.html#//apple_ref/doc/uid/TP30001163-CH22-SW8)
!> for more details.
